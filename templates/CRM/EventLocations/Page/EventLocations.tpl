{*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{if $action eq 1 or $action eq 2 or $action eq 8}
   {include file="CRM/EventLocations/Form/EventLocations.tpl"}
{else}
{crmRegion name="crm-eventlocations-selector-pre"}
{/crmRegion}
<div class="crm-eventlocations-{$context}">
  <div id="help">
    {ts}Add/Edit/Delete event locations.{/ts}
  </div>
  <div class="action-link">
    {crmButton p="civicrm/eventlocations" q="action=add&reset=1" id="eventlocations"  icon="plus-circle"}{ts}Add Location{/ts}{/crmButton}
  </div>
  <table
      class="crm-eventlocations-selector-{$context} crm-ajax-table"
    data-ajax="{crmURL p='civicrm/ajax/getallEventLocations' q='reset=1'}">
    <thead>
    <tr>
      <th data-data="name" class='crm-eventlocations-name'>{ts}Address Name{/ts}</th>
      <th data-data="street_address" class='crm-eventlocations-street_address'>{ts}Street Address{/ts}</th>
      <th data-data="supplemental_address_1" class='crm-eventlocations-supplemental_address_1'>{ts}Supplement Address 1{/ts}</th>
      <th data-data="supplemental_address_2" class='crm-eventlocations-supplemental_address_2'>{ts}Supplement Address 2{/ts}</th>
      <th data-data="supplemental_address_3" class='crm-eventlocations-supplemental_address_3'>{ts}Supplement Address 3{/ts}</th>
      <th data-data="city" class='crm-eventlocations-city'>{ts}City{/ts}</th>
      <th data-data="state_province_id" class='crm-eventlocations-state_province_id'>{ts}State/Province{/ts}</th>
      <th data-data="country_id" class='crm-eventlocations-scountry_id'>{ts}Country{/ts}</th>
      <th data-data="is_active" class='crm-eventlocations-is_active'>{ts}Is Active?{/ts}</th>
      <th data-data="links" data-orderable="false" class='crm-eventlocations-links'></th>
    </tr>
    </thead>
  </table>
</div>
{crmRegion name="crm-eventlocations-selector-post"}
{/crmRegion}
{/if}
