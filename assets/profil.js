
$(document).ready(()=>{

    $('#loupe').children('div').removeClass('mb-6')
    $('#loupe').children('div').addClass('mb-2')

    let div =$('#loupe').children('div')
    div.children('div').removeClass('mb-6')
    
    $('#btnFiltre').click((e)=>{
        e.preventDefault();
        if($('#filtre').css('display') == 'none'){
            $('#contact').css("display", "none") 
            $('#filtre').fadeIn();
            
        }else{
            $('#filtre').css("display", "none")
            $('#contact').fadeIn();
        }
    })

    $('#btnRechercher').click(()=>{
        $('form[name="loupe"]').submit()
    })

    $('#filter_Rechercher').click(()=>{
        $('#filter_Nom').val($('#loupe_Nom').val())
    })
});