jQuery(document).ready(function($){
    // Set weight field as required.
	$('#_weight').prop('required',true);
    
    $("#publish").click(function() {
        let weight = $('#_weight').val();
        if(weight){
            console.log(weight);
        }else{
            $(".shipping_options.shipping_tab a").click();
        }
    })
});