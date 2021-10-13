<?php

class CRM_EventLocations_Utils {
  /**
   * The action links that we need to display for the browse screen.
   *
   * @var array
   */
  static $_links = NULL;

  /**
   * Get action Links.
   *
   * @return array
   *   (reference) of action links
   */
  public static function getlinks() {
    if (!(self::$_links)) {
      self::$_links = [
        CRM_Core_Action::UPDATE => [
          'name' => ts('Edit'),
          'url' => 'civicrm/eventlocations',
          'qs' => 'action=update&id=%%id%%&reset=1&locid=%%locid%%',
          'title' => ts('Edit Event Locations'),
        ],
        CRM_Core_Action::DELETE => [
          'name' => ts('Delete'),
          'url' => 'civicrm/eventlocations',
          'qs' => 'action=delete&id=%%id%%&locid=%%locid%%',
          'title' => ts('Delete Event Locations'),
        ],
      ];
    }
    return self::$_links;
  }

  /**
   * Generate list of locations.
   */
  public static function getLocations($id = NULL) {
    $locations = $params = [];
    $whereClause = '';
    $separator = ' :: ';
    if ($id) {
      $whereClause = ' AND lb.id = %1 ';
      $separator = ', ';
      $params = [1 => [$id, 'Integer']];
    }
    $query = "
      SELECT CONCAT_WS(
          '{$separator}',
          ca.name,
          ca.street_address,
          ca.city,
          ca.supplemental_address_1,
          ca.supplemental_address_2,
          ca.supplemental_address_3,
          sp.name,
          country.name
        ) title,
        lb.id
      FROM   civicrm_loc_block lb
      INNER JOIN civicrm_address ca ON lb.address_id = ca.id {$whereClause}
      LEFT  JOIN civicrm_state_province sp ON ca.state_province_id = sp.id
      LEFT  JOIN civicrm_country country ON ca.country_id = country.id
      LEFT JOIN civicrm_loc_block_entity clbe ON clbe.loc_block_id = lb.id
      WHERE clbe.is_active = 1
      ORDER BY sp.name, ca.city, ca.street_address ASC
  ";

    $dao = CRM_Core_DAO::executeQuery($query, $params);
    while ($dao->fetch()) {
      $locations[$dao->id] = $dao->title;
    }
    return $locations;
  }

  /**
   * Generate list of locations.
   */
  public static function getAllLocations() {
    $getParams = CRM_Core_Page_AJAX::defaultSortAndPagerParams();
    $sortBy = CRM_Utils_Array::value('sortBy', $getParams);
    if ($sortBy) {
      $sortBy = 'address_id.' . $sortBy;
      $sortBy = str_replace('state_province_id', 'state_province_id.name', $sortBy);
      $sortBy = str_replace('country_id', 'country_id.name', $sortBy);
    }
    if (empty($sortBy)) {
      $sortBy = 'address_id.name ASC';
    }
    $params = [
      'return' => [
        "address",
      ],
      'address_id' => ['IS NOT NULL' => 1],
      'options' => [
        'sort' => $sortBy,
        'offset' => ($getParams['page'] - 1) * $getParams['rp'],
        'limit' => $getParams['rp'],
      ],
    ];
    $result = civicrm_api3('LocBlock', 'get', $params);
    $locations = [];
    $mask = array_sum(array_keys(self::getlinks()));
    foreach ($result['values'] as $key => $values) {
      if (empty($values['address'])) {
        continue;
      }
      $address = $values['address'];
      $replace = [
        'id' => $address['id'],
        'locid' => $values['id'],
      ];
      $isActive = self::isActive($values['id']);
      $class = ' crm-entity ';
      if (!$isActive) {
        $class .= ' disabled';
      }
      $locations[] = [
        'DT_RowId' => $address['id'],
        'DT_RowClass' => $class,
        'DT_RowAttr' => [
          'data-entity' => 'address',
          'data-id' => $address['id'],
        ],
        'name' => $address['name'],
        'street_address' => $address['street_address'],
        'is_active' => ($isActive == 1) ? ts('Yes') : ts('No'),
        'supplemental_address_1' => $address['supplemental_address_1'],
        'supplemental_address_2' => $address['supplemental_address_2'],
        'supplemental_address_3' => $address['supplemental_address_3'],
        'city' => $address['city'],
        'state_province_id' => CRM_Core_PseudoConstant::getLabel(
          'CRM_Core_DAO_Address',
          'state_province_id',
          $address['state_province_id']
        ),
        'country_id' => CRM_Core_PseudoConstant::getLabel(
          'CRM_Core_DAO_Address',
          'country_id',
          $address['country_id']
        ),
        'links' => CRM_Core_Action::formLink(
          self::getlinks(),
          $mask,
          $replace,
          ts('more'),
          FALSE,
          'eventlocations.selector.row',
          'EventLocations',
          $rid
        ),
      ];
    }
    $count = civicrm_api3('LocBlock', 'getcount', ['address_id' => ['IS NOT NULL' => 1]]);
    $locationsDT = [
      'data' => $locations,
      'recordsTotal' => $count,
      'recordsFiltered' => $count,
    ];
    CRM_Utils_JSON::output($locationsDT);
  }

  public static function getLocBlock() {
    // i wish i could retrieve loc block info based on loc_block_id,
    $location = NULL;
    if ($_REQUEST['lbid']) {
      // second parameter is of no use, but since required, lets use the same variable.
      $result = civicrm_api3('LocBlock', 'getsingle', [
        'return' => ["all"],
        'id' => $_REQUEST['lbid'],
      ]);
      foreach ([
        'address' => ['address', 'address_2'],
        'phone' => ['phone', 'phone_2'],
        'email' => ['email', 'email_2']
      ] as $k => $v) {
        $index = 1;
        foreach ($v as $aKey) {
          if (!empty($result[$aKey])) {
            $location[$k][$index] = $result[$aKey];
          }
          else {
            $location[$k][$index] = [];
          }
          $index++;
        }
      }
    }

    $result = [];
    $addressOptions = CRM_Core_BAO_Setting::valueOptions(CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
      'address_options', TRUE, NULL, TRUE
    );
    // lets output only required fields.
    foreach ($addressOptions as $element => $isSet) {
      if ($isSet && (!in_array($element, [
        'im',
        'openid',
      ]))) {
        if (in_array($element, [
          'country',
          'state_province',
          'county',
        ])) {
          $element .= '_id';
        }
        elseif ($element == 'address_name') {
          $element = 'name';
        }
        $fld = "address[1][{$element}]";
        $value = CRM_Utils_Array::value($element, $location['address'][1]);
        $value = $value ? $value : "";
        $result[str_replace(array(
          '][',
          '[',
          "]",
        ), ['_', '_', ''], $fld)] = $value;
      }
    }

    foreach ([
      'email',
      'phone_type_id',
      'phone',
    ] as $element) {
      $block = ($element == 'phone_type_id') ? 'phone' : $element;
      for ($i = 1; $i < 3; $i++) {
        $fld = "{$block}[{$i}][{$element}]";
        $value = CRM_Utils_Array::value($element, $location[$block][$i]);
        $value = $value ? $value : "";
        $result[str_replace([
          '][',
          '[',
          "]",
        ], ['_', '_', ''], $fld)] = $value;
      }
    }

    // set the message if loc block is being used by more than one event.
    $result['count_loc_used'] = CRM_Event_BAO_Event::countEventsUsingLocBlockId($_REQUEST['lbid']);

    CRM_Utils_JSON::output($result);
  }

  public static function installTableData() {
    CRM_Core_DAO::executeQuery("
      CREATE TABLE IF NOT EXISTS `civicrm_loc_block_entity` (
        `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique  ID',
        `loc_block_id` int unsigned    COMMENT 'FK to Loc Block ID.',
        `is_active` tinyint NOT NULL  DEFAULT 1 COMMENT 'Is Active',
        PRIMARY KEY (`id`),
        CONSTRAINT FK_civicrm_loc_block_entity_loc_block_id FOREIGN KEY (`loc_block_id`) REFERENCES `civicrm_loc_block`(`id`) ON DELETE CASCADE
      )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ");
    CRM_Core_DAO::executeQuery("
      INSERT INTO civicrm_loc_block_entity(loc_block_id, is_active)
      SELECT clb.id, 1 FROM `civicrm_loc_block` clb
        LEFT JOIN civicrm_loc_block_entity clbe
          ON clbe.loc_block_id = clb.id
      WHERE clbe.id IS NULL;
    ");
  }

  public static function isActive($locBlockId) {
    $query = " SELECT is_active FROM civicrm_loc_block_entity WHERE loc_block_id = %1";
    return CRM_Core_DAO::singleValueQuery(
      $query,
      [1 => [$locBlockId, 'Integer']]
    );
  }

  public static function saveActive($locBlockId, $isActive) {
    $dao = new CRM_EventLocations_DAO_EventLocationsActive();
    $dao->loc_block_id = $locBlockId;
    $dao->find(TRUE);
    $dao->is_active = $isActive;
    $dao->save();
    $dao->free();
  }

}
