Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};
var URL = window.location.protocol + "//" + window.location.host;
var lod ="<img src='"+URL+"/img/ajax-loader.gif' id='lod'>";
$(document).ready(function(){
    $('#negara').change(function(){
        $('#provinsi').attr('disabled', 'true');
        id=this.value;
        if(id!=''){         
            $(this).parent().append(lod);
            $(this).attr("disabled",true);
            $.ajax({
              url: URL+'/admin/provinsi/list/'+id,          
              type: 'get',
            }).done(function(data){     
                $('#provinsi').find('option').remove();                     
            }).done(function(data){
                $('#provinsi').removeAttr('disabled');
                $('#provinsi').append(data);
                $('#lod').remove();
                $('#negara').attr("disabled",false);
            }).error(function(){
                $('#provinsi').removeAttr('disabled');
                $('#lod').remove();
                $('#negara').attr("disabled",false);
            });
        }
    });
    $('#negarapenerima').change(function(){        
        $('#provinsipenerima').attr('disabled', 'true');
        id=this.value;
        if(id!=''){         
            $(this).parent().append(lod);
            $(this).attr("disabled",true);
            $.ajax({
              url: URL+'/admin/provinsi/list/'+id,          
              type: 'get',
            }).done(function(data){     
                $('#provinsipenerima').find('option').remove();                     
            }).done(function(data){
                $('#provinsipenerima').removeAttr('disabled');
                $('#provinsipenerima').append(data);
                $('#lod').remove();
                $('#negarapenerima').attr("disabled",false);
            }).error(function(){
                $('#negarapenerima').removeAttr('disabled');                
                $('#lod').remove();
                $('#provinsipenerima').attr("disabled",false);
            });
        }
    });

    $('#show').change(function(){
        id=this.value;      
        link = $(this).attr('data-rel');
        arr = new Array();
        data = getQueryVariable();
        qry = '';
        if(data['page']!=undefined){
            qry = qry+'?page='+data['page'];
        }
        if(data['show']!=undefined){
            if(qry==''){
                qry = qry+'?show='+id;
            }               
            else{
                qry = qry+'&show='+id;
            }
                
        }else{
            if(qry==''){
                qry = qry+'?show='+id;
            }
            else{
                qry = qry+'&show='+id;
            }

        }
        if(data['view']!=undefined){
            if(qry==''){
                qry = qry+'?view='+data['view'];
            }
            else{
                qry = qry+'&view='+data['view'];
            }
        }
        window.location = link+qry;
    });

    $('#provinsi').change(function(){
        $('#kota').attr('disabled', 'true');
        id=this.value;
        if(id!=''){     
            $(this).parent().append(lod);
            $(this).attr("disabled",true);
            $.ajax({
              url: URL+'/admin/kabupaten/list/'+id,     
              type: 'get',
            }).done(function(data){     
                $('#kota').find('option').remove();                     
            }).done(function(data){
                $('#kota').removeAttr('disabled');
                $('#kota').append(data);
                $('#lod').remove();
                $('#provinsi').attr("disabled",false);
            }).error(function(){
                $('#kota').removeAttr('disabled');
                $('#lod').remove();
                $('#provinsi').attr("disabled",false);
            })
        }
    });
    $('#provinsipenerima').change(function(){
        $(this).parent().append(lod);
        $('#kotapenerima').attr('disabled', 'true');
        id=this.value;
        if(id!=''){     
            $(this).attr("disabled",true);
            $.ajax({
              url: URL+'/admin/kabupaten/list/'+id,     
              type: 'get',
            }).done(function(data){     
                $('#kotapenerima').find('option').remove();                     
            }).done(function(data){
                $('#kotapenerima').removeAttr('disabled');
                $('#kotapenerima').append(data);
                $('#lod').remove();
                $('#provinsipenerima').attr("disabled",false);
            }).error(function(){
                $('#kotapenerima').removeAttr('disabled');
                $('#lod').remove();
                $('#provinsipenerima').attr("disabled",false);
            });
        }
    });

    $('#addorder').submit(function(){
        var pathArray = window.location.pathname.split( '/' );
        var id = pathArray[pathArray.length-1].split('-');
        var produkId = id[0];
        var qty = $('#addorder input[name="qty"]').val();
        var opsi = '';
        var namaopsi = '';
        valid = true;
        var n = ~~Number(qty);
        status = String(n) === qty && n > 0;

        opsi = $('#addorder select').val();
        if(opsi==''){
            $('#addorder select').focus();
            noty({"text":'Pilih salah satu opsi produk.',"layout":"center","type":'error','speed': 100});       
            valid = false;
        }
        if(status=='false'){
            noty({"text":'Quantity salah.',"layout":"center","type":'error','speed': 100});     
            valid=false;
        }
        if(valid==true)
        {
            //$('#shoppingcartplace').focus();
            $("#cart_dialog").dialog({
                title : 'Terima Kasih Sudah Berbelanja di Toko Kami.',
                width: 'auto', // overcomes width:'auto' and maxWidth bug
                height: 'auto',
                minWidth : 500,
                maxWidth: 500,
                modal: true,
                fluid: true, //new option
                resizable: false,
                //closeOnEscape: false,
                draggable: false,
                open: function(event, ui){ 
                    $(".ui-dialog-titlebar").hide();
                    fluidDialog();                          
                    $.ajax({                
                        url: URL+'/cart',
                        type: 'post',
                        data: {produkId:produkId,namaopsi:namaopsi,opsi:opsi,qty:qty}
                    }).done(function(data){
                        //alert(data);
                        if(data=='stok'){
                            noty({"text":'Maaf, Stok tidak mencukupi.',"layout":"center","type":'error','speed': 100});
                            $( "#cart_dialog" ).dialog('close');        
                        }else if(data=='opsi'){
                            noty({"text":'Maaf, Opsi tidak ditemukan.',"layout":"center","type":'error','speed': 100});     
                            $( "#cart_dialog" ).dialog('close');        
                        }
                        else{
                            //noty({"text":'Selamat, Item sudah tertambah ke cart.',"layout":"center","type":'success','speed': 100});      
                            $('#shoppingcartplace').html(data['cart']);
                            $('.ui-dialog-content').html(data['detail']);                               
                        }
                        //$('#addorder input[type="submit"]').button('reset');
                        //$('.add_cart').button('reset');

                    }).done(function(){
                        fluidDialog();
                    });
                },
                beforeClose: function( event, ui ) {
                    $('.ui-dialog-content').html('<img src="'+URL+'/img/spinner-mini.gif" style="position:relative;margin:100px">');
                }

            });
            //$('#addorder input[type="submit"]').button('loading');
            //$('.add_cart').button('loading');

            
        }
        return false;
    });

    //tampilkan error noty
    var msg = $('#message');
    if(msg.length){
        type = $(msg).attr('class');        
        text = $(msg).html();
        noty({"text":text,"layout":"top","type":type});    
    }

    //edit cart qty
    var temp='';
    var temp2='';
    $('body').on('focus','.cartqty',function(a){
        temp2 = this.value;
        if(temp==''){
            temp=temp2;
        }       
    });
    $('body').on('keyup, change ','.cartqty',function(a){        
        this.value = this.value.replace(/[^0-9\.]+/g, '');
        if(this.value==''){
            this.value='';
        }
        qty = this.value;
        rowid = this.name;
        var input = this;
        if(a.keyCode!=8 && temp!=qty){
            if(qty!='' && qty!=0 && rowid!=''){
                this.readOnly = true;       
                $('#form1').button('loading');
                $.ajax({
                    url: URL+'/cart/'+rowid,            
                    type: 'PUT',
                    data: {qty:qty}
                }).done(function(data){                     
                    if(data!='false'){
                        data = data.split(';')
                        if ($('#subtotalcart').length) 
                        {
                            var place = $('.'+rowid);
                            var harga = $('.'+rowid).html();
                            harga = harga.replace(/[0-9]/g, '');
                            harga = harga.replace(/\./g,"");
                            harga = harga.replace(/<(?:.|\n)*?>/gm, '');                
                            place.html(harga+' '+parseInt(data[0]).formatMoney(0,'.',''));  
                            $('#subtotalcart').html(harga+' '+parseInt(data[1]).formatMoney(0,'.',''));   
                        }                                   
                        temp = qty;
                        noty({"text":'Selamat, Cart berhasil di update. Kalkulasi ulang.',"layout":"center","type":'success'});
                    }else{
                        noty({"text":'Maaf, Quantity tidak mencukupi.',"layout":"center","type":'error'});
                        $(input).val(temp);
                        temp2 = temp;
                    }
                    
                    input.readOnly = false;
                }).done(function(data){
                    if(data!='false'){
                        if ($('#subtotalcart').length>0) 
                        {
                            tarif = $('#ekspedisilist').val();     
                            eks = $('#tujuan').val();       
                            kupon = $('#kuponbtn').html();
                            if(eks!='' && tarif!=''){
                                $('#ekspedisibtn').trigger('click');                    
                            }
                            if(kupon=='Cancel'){
                                $('#kuponbtn').trigger('click');
                            }
                        }
                    }
                }).done(function(){   
                    if ($('#subtotalcart').length>0) 
                    {
                        calculate();
                    }          
                    $('#form1').button('reset');
                }).error(function(){
                    noty({"text":'Maaf, Terjadi kesalahan.',"layout":"center","type":'error'});
                    $('#form1').button('reset');
                });
            }   
        }
    });

    //find kupon
    var btnval='';
    $('#kuponbtn').click(function(e){

        var kode = $('#kuponplace').val();
        var btn = $('#kuponbtn');       
        if(btn.val()=='Cancel' || btn.html()=='Cancel'){
            var total = $('#subtotalcart').html();
            format = total.replace(/[0-9]/g, '');
            format = format.replace(/\./g,"");
            format = format.replace(/<(?:.|\n)*?>/gm, '');
            btn.button('loading');
            $.ajax({
                url: URL+'/cart/checkdiskon/'+kode,         
                type: 'get',
                data: {status:'cancel'}
            }).done(function(data){
                $("#kuponplace").attr("disabled",false);
                $('#kupontext').html(format+' 0');
            }).done(function(){
                btn.val('Pilih Kupon');
                btn.html('Pilih Kupon');                
                calculate();
            }).done(function(){
                if (e.originalEvent === undefined)
                  {
                    $('#kuponbtn').trigger('click');
                  }else{
                   noty({"text":'Kupon berhasil di cancel.',"layout":"top","type":'error','speed': 100});      
                  }
                 $('#diskonstatus').remove();
                 btn.button('reset');
                 btn.html('Pakai Kupon');
            });

        }else{

            btnval = btn.val(); 
            if(kode!='' && kode!=undefined){
                $(this).button('loading');
                $.ajax({
                    url: URL+'/cart/checkdiskon/'+kode,         
                    type: 'get'
                }).done(function(data){     
                    var potongan = 0;
                    if(data=='false'){
                        noty({"text":'Maaf, Kode diskon tidak ditemukan.',"layout":"top","type":'error','speed': 100});     
                        $('#kupontext').html('Kode diskon tidak ditemukan.');
                        btn.button('reset');
                    }                   
                    else if(data=='false2'){
                        noty({"text":'Maaf, Order Tidak Memenuhi minimal belanja.',"layout":"top","type":'error','speed': 100});        
                        $('#kupontext').html('Tidak Memenuhi minimal belanja.');
                        btn.button('reset');
                    }                   
                    else if(data=='false3'){
                        noty({"text":'Maaf, Kupon diskon anda sudah expired.',"layout":"top","type":'error','speed': 100});     
                        $('#kupontext').html('Kupon diskon sudah expired.');
                        btn.button('reset');
                    }                   
                    else if(data=='false4'){
                        noty({"text":'Maaf, Kupon tidak ditemukan untuk produk anda.',"layout":"top","type":'error','speed': 100});     
                        $('#kupontext').html('Kupon tidak berlaku untuk produk anda.');
                        btn.button('reset');
                    }   
                    else if(data=='false5'){
                        noty({"text":'Maaf, Kupon sudah terpakai.',"layout":"top","type":'error','speed': 100});     
                        $('#kupontext').html('Kupon sudah terpakai.');
                        btn.button('reset');
                    }                   
                    else{                       
                        var total = $('#subtotalcart').html();
                        totalbelanja = total.replace(/[^\0-9]/ig, "");
                        totalbelanja = totalbelanja.replace(/\./g,"");


                        format = total.replace(/[0-9]/g, '');
                        format = format.replace(/\./g,"");
                        format = format.replace(/<(?:.|\n)*?>/gm, '');
                        
                        if(data[2]==2){
                            $('#kupontext').html(format+' '+data[1]+' ('+data[3]+'%)');

                        }else{
                            potongan = parseInt(data[1]);
                            $('#kupontext').html(format+' '+potongan.formatMoney(0,'.','.'));
                        }           
                        if (e.originalEvent === undefined)
                          {
                        
                          } else{
                            noty({"text":'Selamat, Kupan ditemukan.',"layout":"top","type":'error','speed': 100});          
                          }  
                        
                        btn.button('reset');        
                        $("#kuponplace").attr("disabled",true);
                        $("#kuponplace").parent().append('<input type="hidden" id="diskonstatus" value="1">');                      
                        $("#kuponbtn").prop('value', 'Cancel');
                        $("#kuponbtn").html('Cancel');
                        calculate();
                    }
                    //$('#potongan').val(potongan);
                });
            }else{
                $('#kuponplace').focus();
            }           
        }

        return false;
    });

    //js pilih provinsi
    $('#ekspedisibtn').click(function(){    
            var btn = $('#ekspedisibtn');
            tujuan = $('#tujuan').val();
            tampung = $('#ekspedisilist').val();
            if(tujuan !=''){
                $('#ekspedisiplace').slideUp(100,function(){
                    btn.button('loading');
                    $.ajax({
                        url: URL+'/cart/checkekspedisi/'+tujuan ,           
                        type: 'get'
                    }).done(function(data){
                        //$('#ekspedisiplace').find('label').remove();  
                        $('#ekspedisiplace').slideDown(100);
                        $('#result_ekspedisi').remove();
                    }).done(function(data){                                 
                        $('#ekspedisiplace').html(data);              
                    }).done(function(data){                        
                        $('#statusEkspedisi').val('0');
                        if(tampung!='' && tampung!=undefined){
                            $('#ekspedisitext').html(0);
                            tampungs = tampung.split(';');
                            $('input[name="ekspedisilist"]').each(function(){
                                cek = this.value.split(';');
                                if(cek[0]==tampungs[0]){
                                    $(this).trigger('click');
                                }
                            }); 
                        }                       
                        btn.button('reset');
                    }).error(function(xhr, ajaxOptions, thrownError) {
                        if(xhr.status==500){
                            noty({"text":'Maaf, terjadi kesalahan dalam pencarian expedisi. Periksa koneksi internet anda!',"layout":"center","type":'error','speed': 100});       
                        }else{
                            noty({"text":'Maaf, terjadi kesalahan. Silakan Coba lagi!',"layout":"center","type":'error','speed': 100});       
                        }
                        $('#statusEkspedisi').val('0');
                        $('#ekspedisitext').html("Rp. 0");
                        btn.button('reset');
                    }); 
                });
                
            }
        });

    //js checked radio
    $('body').on('click','input[name="ekspedisilist"]',function(){
        tujuan = $('#tujuan').val();
        var total = $('#subtotalcart').html();
        format = total.replace(/[0-9]/g, '');
        format = format.replace(/\./g,"");
        format = format.replace(/<(?:.|\n)*?>/gm, '');
        value = this.value;
        eks = this.value.split(';');
        $('#ekspedisitext').html(format+' '+parseInt(eks[1]).formatMoney(0,'.'));
        calculate();
        $.ajax({
            url: URL+'/cart/addekspedisi/'+this.value,          
            type: 'get',
            data : {tujuan:tujuan}
        }).done(function(data){
            $('#statusEkspedisi').val(1);
            $('#ekspedisilist').val(value);
        });
    });

    $('[name="checkout"]').submit(function(){
        status =  ($('#statusPengiriman').val());
        ekspedisi =  ($('#statusEkspedisi').val());
        valid=true;
        if(ekspedisi==0 && status==1){
            valid=false;
        }
        if(valid==false){
            noty({"text":'Maaf, Anda belum memilih ekspedisi.',"layout":"top","type":'error','speed': 100});        
        }
        return valid;
    });
    $('#form1').click(function(){
        $(this).button('loading');
        $('[name="checkout"]').submit();
        $(this).button('reset');
    })
    $('[name="pembayaran"]').submit(function(){
        valid=true;     
        bayar = $('[name="tipepembayaran"]:checked').val();
        if(bayar==undefined){
            noty({"text":'Silakan pilih salah satu tipe pembayaran.',"layout":"top","type":'error','speed': 100});           
            valid =false;
        }
        return valid;
    });
    pem='';
    $('[name="tipepembayaran"]').click(function(){
       id = this.value;
       if(pem==''){
            $("#"+id).show(1);
            pem=id;
       }else{
            $("#"+pem).hide(10,function(){
                $("#"+id).show(1);
            });
            pem=id;
       }

    });
    //cek data penerima 
    if($('input[name="tipepembayaran"]').length){
        var id = $('input[name="tipepembayaran"]:checked').val();
        if(id!=undefined){
            $("#"+id).show(1);
            pem=id;
        }
    }
    if($('input[name="tipepembayaran"]:checked').val()==0){
        $('#datapenerima').slideUp('fast');
    }
    if($('input[name="statuspenerima"]:checked').val()==0){
        $('#datapenerima').slideUp('fast');
    }
    $('input[name="statuspenerima"]').click(function(){ 
        if(this.value==0){
            $('#datapenerima').slideUp('fast');
        }else{
            $('#datapenerima').slideDown('fast');
            $('html, body').animate({ scrollTop: $("#datapenerima").offset().top -20}, 500); 
        }
    });
    

    $('[name="pengiriman"]').submit(function(){
        valid = true;
        nama = $('form[name="pengiriman"] [name="nama"]').val();
        email = $('form[name="pengiriman"] [name="email"]').val();      
        telp = $('form[name="pengiriman"] [name="telp"]').val();
        alamat = $('form[name="pengiriman"] [name="alamat"]').val();
        negara = $('form[name="pengiriman"] [name="negara"]').val();
        provinsi = $('form[name="pengiriman"] [name="provinsi"]').val();
        kota = $('form[name="pengiriman"] [name="kota"]').val();
        kodepos = $('form[name="pengiriman"] [name="kodepos"]').val();
        var atpos=email.indexOf("@");
        var dotpos=email.lastIndexOf(".");
        if(nama=='' || email=='' || telp=='' || alamat=='' || negara=='' || provinsi=='' || kota=='' || kodepos==''){           
            noty({"text":'Data anda masih belum lengkap.',"layout":"top","type":'error','speed': 100});
            valid=false;

        }
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length){
            noty({"text":'Alamat email tidak valid.',"layout":"top","type":'error','speed': 100});
            valid= false;         
        }
        if(isNaN(kodepos)){
            noty({"text":'Kode pos tidak valid valid.',"layout":"top","type":'error','speed': 100});
            valid= false;         
        }
        if($('input[name="statuspenerima"]:checked').val()==1){            
            if($('[name="namapenerima"]').val()==''){
                $('[name="namapenerima"]').focus();
                valid = false;
            }
            if($('[name="telppenerima"]').val()==''){
                $('[name="telppenerima"]').focus();
                valid = false;
            }
            if($('[name="alamatpenerima"]').val()==''){
                $('[name="alamatpenerima"]').focus();
                valid = false;
            }
            if($('[name="negarapenerima"]').val()==''){
                $('[name="negarapenerima"]').focus();
                valid = false;
            }
            if($('[name="provinsipenerima"]').val()==''){
                $('[name="provinsipenerima"]').focus();
                valid = false;
            }
            if($('[name="kotapenerima"]').val()==''){
                $('[name="kotapenerima"]').focus();
                valid = false;
            }
            if(valid==false){
                noty({"text":'Data penerima masih belum lengkap.',"layout":"top","type":'error','speed': 100});     
                $('html, body').animate({ scrollTop: $("#datapenerima").offset().top-50 },1000);                 
            }
        }
        return valid;
    });
    $('[name="finish"]').submit(function(){
        konf = window.confirm("Pastikan semua data anda sudah benar.")
        if(!konf){
            return false;
        }
    });

    //
    calculate();

});

function calculate(){
    var total =0;
    if($('#ekspedisitext').length){
        var total = $('#subtotalcart').html();
    }
    if($('#ekspedisitext').length){
        var ekspedisi = $('#ekspedisitext').html();
    }else{
        var ekspedisi = 0;
    }
    if($('#kupontext').length){
        var kupon = $('#kupontext').html(); 
    }else{
        var kupon = 0;
    }
    if($('#pajaktext').length){
        var pajak = $('#pajaktext').html(); 
    }else{
        var pajak = 0;
    }
    if($('#kodeuniktext').length){
        var kode = $('#kodeuniktext').html();   
    }else{
        var kode = 0;
    }
    format = total.replace(/[0-9]/g, '');
    format = format.replace(/\./g,"");
    format = format.replace(/<(?:.|\n)*?>/gm, '');
    var a = getInt(total) + getInt(ekspedisi) - getInt(kupon) + getInt(kode);
    a = a + (a*getInt(pajak)/100);
    $('#totalcart').html(format + ' '+a.formatMoney(0,'.'))
}

function getInt(total){
    total = total.toString();
    totalbelanja = total.replace(/[^\0-9]/ig, "");
    totalbelanja = totalbelanja.replace(/\./g,"");
    if(totalbelanja=='' || totalbelanja==null){
        totalbelanja=0;
    }
    rs = parseInt(totalbelanja);
    if(isNaN(rs)){
        rs = 0;
    }
    return rs;
}
function deletecart(id){
    //calculate();
     $("#cart_dialog").dialog({
                title : 'Terima Kasih Sudah Berbelanja di Toko Kami.',
                width: 'auto', // overcomes width:'auto' and maxWidth bug
                height: 'auto',
                minWidth : 50,
                maxWidth: 500,
                maxHeight: 50,
                modal: true,
                fluid: true, //new option
                resizable: false,
                closeOnEscape: false,
                draggable: false,                
                open: function(event, ui){ 
                    $(".ui-dialog-titlebar").hide();
                    fluidDialog();       
                    $('#form1').button('reset');                   
                    $.ajax({
                        url: URL+'/cart/delete/'+id,            
                        type: 'get'
                    }).done(function(data){
                         if ($('#subtotalcart').length) 
                        {
                             $('#subtotalcart').html(data['total']);  
                            calculate();
                        }
                        if(data['jumlah']==0)
                        {
                            window.location = URL+"/checkout";
                        }
                        else
                        {
                            if ($('#subtotalcart').length) 
                            {
                                tarif = $('#ekspedisilist').val();     
                                eks = $('#tujuan').val();       
                                kupon = $('#kuponbtn').html();
                                if(eks!='' && tarif!=''){
                                    $('#ekspedisibtn').trigger('click');                    
                                }
                                if(kupon=='Cancel'){
                                    $('#kuponbtn').trigger('click');
                                }
                            }
                            $('#cart'+id).remove();
                        }
                    }).done(function(){
                        $("#cart_dialog").dialog('close');
                         $('#form1').button('reset');
                    }).error(function(){
                        $("#cart_dialog").dialog('close');
                        noty({"text":'Maaf, terjadi kesalahan..',"layout":"center","type":'error','speed': 100});       
                        $('#form1').button('reset');
                    }); 
                }
            });
    return false;
}
function btnReset(a){
    a.prop("disabled",false);
}
function getQueryVariable(url) {
    var query = window.location.search.substring(1);
    var vars = query.split('&');
    var rs = new Array();
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        rs[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
    return rs;
}

function windowsNew(href){
    window.open(href, 'mywin', 'left=20, top=20, width=500, height=500, toolbar=1, resizable=0'); 
    return false;
}



////
$("#dButton").click(function () {
  
});


// run function on all dialog opens
$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
    fluidDialog();
});

// remove window resize namespace
$(document).on("dialogclose", ".ui-dialog", function (event, ui) {
    $(window).off("resize.responsive");
});

function fluidDialog() {
    var $visible = $(".ui-dialog:visible");
    // each open dialog

        var $this = $visible;
        if($("#cart_dialog").dialog('option','maxWidth') && $("#cart_dialog").dialog('option','width')){
            $this.css("max-width",$("#cart_dialog").dialog('option','maxWidth'));
            //reposition dialog
            $( "#cart_dialog" ).dialog( "option", "position", $( "#cart_dialog" ).dialog( "option", "position" ));

        }
        if ($("#cart_dialog").dialog("option","fluid")) {
            // namespace window resize
            $(window).on("resize.responsive", function () {
                var wWidth = $(window).width();
                // check window width against dialog width
                if (wWidth < $("#cart_dialog").dialog('option','maxWidth') + 50) {
                    // keep dialog from filling entire screen
                    $this.css("width", "90%");
                    
                }
              //reposition dialog
              $("#cart_dialog").dialog('option','position',$( "#cart_dialog" ).dialog( "option", "position" ));              
            });
        }

}
function close_dialog(){
    $( "#cart_dialog" ).dialog('close');
}