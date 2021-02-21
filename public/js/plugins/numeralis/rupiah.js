$(document).ready(function(){
	/* Tanpa Rupiah */
	var tanpa_rupiah = $('.tanpa-rupiah');

	/*tanpa_rupiah.keyup(function(e)
	{
		tanpa_rupiah.val(formatRupiah(this.value));
	});
	
	tanpa_rupiah.keydown(function(event)
	{
		limitCharacter(event);
	});

	tanpa_rupiah.change(function(e)
	{
		tanpa_rupiah.val(formatRupiah(this.value));
	});*/
	
	$(document).on('keyup', '.tanpa-rupiah', function(event) {
		$(this).val(formatRupiah(this.value));
	});
	
	$(document).on('keydown', '.tanpa-rupiah', function(event) {
		limitCharacter(event);
	});
	
	$(document).on('keydown', '.nomor-aja', function(event) {
		limitCharacter(event);
	});

	$(document).on('change', '.tanpa-rupiah', function(event) {
		$(this).val(formatRupiah(this.value));
	});
	
	/* Dengan Rupiah */
	var dengan_rupiah = $('.dengan-rupiah');
	/*dengan_rupiah.keyup(function(e)
	{
		dengan_rupiah.val(formatRupiah(this.value, 'Rp. '));
	});
	
	dengan_rupiah.keydown(function(event)
	{
		limitCharacter(event);
	});*/
	$(document).on('keyup', '.dengan-rupiah', function(event) {
		$(this).val(formatRupiah(this.value, 'Rp. '));
	});
	
	$(document).on('keydown', '.dengan-rupiah', function(event) {
		limitCharacter(event);
	});
	
	/* Fungsi */
	formatRupiah = function (bilangan, prefix)
	{
		var pre = '';
		if(bilangan.toString().indexOf("-") > -1){
			pre = "-";
		}

		var number_string = bilangan.toString().replace(/[^,\d]/g, ''),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? pre + rupiah : (rupiah ? prefix + pre +rupiah : '');
	}
	
	limitCharacter = function (event)
	{
		key = event.which || event.keyCode;
		if ( key != 188 // Comma
			 && key != 8 // Backspace
			 && key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
			 && (key < 48 || key > 57) // Non digit
			 // Dan masih banyak lagi seperti tombol del, panah kiri dan kanan, tombol tab, dll
			) 
		{
			event.preventDefault();
			return false;
		}
	}
	
	toNumber = function (digit){
		return digit.replace(/[^0-9]/g, '');
	}

	convertToRupiah = function (angka)
	{
		var rupiah = '';		
		var angkarev = angka.toString().split('').reverse().join('');
		for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
		return rupiah.split('',rupiah.length-1).reverse().join('');
	}
})
