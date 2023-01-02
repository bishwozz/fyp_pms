<a class="btn btn-sm btn-primary" target = "_blank" onclick="printAll()"   data-style="zoom-in"><span class="ladda-label"><i class="fa fa-print"></i> Print All</span></a>

<script>
    function printAll() {
      let qs = '';
      if($('#filter_supplier_id').val() !== '') {
        qs += 'supplier_id=' + $('#filter_supplier_id').val();
      }
      if($('#filter_pharmaceutical_id').val() !== '') {
        qs += (qs !== '' ? '&' : '') + 'pharmaceutical_id=' + $('#filter_pharmaceutical_id').val();
      }
      
      if($('#text-filter-brand-name').length && $('#text-filter-brand-name').val() !== '') {
        qs += (qs !== '' ? '&' : '') + 'brand_name=' + $('#text-filter-brand-name').val();
      }
      if($('#search').length && $('#search').val() !== '') {
        qs += (qs !== '' ? '&' : '') + 's=' + $('#search').val();
      }

      if(qs !== '') {
        window.open('/admin/inventory/printReport?' + qs);
      }
    }
</script>