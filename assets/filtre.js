$(document).ready(()=>{
    
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