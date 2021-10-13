<div class="crm-block crm-form-block crm-eventlocations-form-block">
  {if $action eq 8}
    <div class="messages status">
      <div class="icon inform-icon"></div>
      {ts}WARNING: Deleting a location cannot be undone.{/ts} {ts}Do you want to continue?{/ts}
    </div>
  {else}
    <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
    {include file="CRM/Contact/Form/Edit/Address.tpl" blockId=1}
    <table class = 'form-layout-compressed'>
      <tr><td>{$form.is_active.label} {$form.is_active.html}</td></tr>
    </table>
  {/if}
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="botttom"}</div>
</div>
