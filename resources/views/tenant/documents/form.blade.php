@extends('tenant.layouts.app')

@push('styles')
    <style type="text/css">
        .v-modal {
            opacity: 0.2 !important;
        }
        .border-custom {
            border-color: rgba(0,136,204, .5) !important;
        }
        @media only screen and (min-width: 768px) { 
        	.inner-wrapper {
			    padding-top: 60px !important;
			}
        }
        .card-header {
		    border-radius: 0px 0px 0px !important;
		}
    </style>
@endpush

@section('content')
      
    <tenant-documents-invoice ref="foo" :is_contingency="{{ json_encode($is_contingency) }}"></tenant-documents-invoice>
@endsection

@push('scripts')
<!-- Incluyendo .js de Culqi Checkout-->
 
 <script>
 
    function culqi() {
        if (Culqi.token) {  

            let token = Culqi.token.id;
            let email= Culqi.token.email; 

            console.log(Culqi.token) 
            console.log(Culqi.getSettings)

            let form = {
                    token:token,
                    description:Culqi.getSettings.description,
                    amount:Culqi.getSettings.amount,
                    email:email,
                    programacion_id:1
                }

            // this.$message('This is a message.');
            
            axios.post(`/paymentonline`, form).then(response => {
                        console.log(response) 
                        alert(response) 
                      
                    }).catch(error => {
                            console.log(error) 

                    }).then(() => {
                    }); 

        } else {  
            console.log(Culqi.error);
        }
    };
</script>

<script type="text/javascript">
	var count = 0;
	$(document).on("click", "#card-click", function(event){
		count = count + 1;
		if (count == 1) {
			$("#card-section").removeClass("card-collapsed");
		}		
	});
</script>
@endpush