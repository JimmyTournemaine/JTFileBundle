$(function(){
	function tvaPlaceholder(value){
		$('#company_tva').attr("placeholder",value);
	}
	
	var input = document.getElementById('company_address');
	var autocomplete = new google.maps.places.Autocomplete(input, {});
	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		/* Set country code and TVA */
		country: {
			var adresses = place.address_components;
			for(var i=0; i<adresses.length; i++){
				adresse = adresses[i];
				for(var j=0; j<adresse.types.length; j++){
					if(adresse.types[j] == "country"){
						var countryField = $('#company_country');
						var tvaField = $('#company_tva');
						var country = adresse.short_name;
						countryField.val(country);
						if(tvaField.val() == "")
							tvaPlaceholder(country+"XXXXXXXXXXX");
						break country;
					}
				}
			}
		}
		/* Set phone number */
		var phoneField = $('#company_phone');
		var phone = place.international_phone_number;
		if(phone !== 'undefined' && phoneField.val() == "")
			phoneField.val(phone);
		
		/* Set website */
		var websiteField = $('#company_website');
		var website = place.website;
		if(website !== 'undefined' && websiteField.val() == "")
			websiteField.val(website);
	});
})