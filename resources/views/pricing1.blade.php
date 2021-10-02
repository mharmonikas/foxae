@include('header')
  
<style>
@media only screen and (max-width: 480px){
#price-plan-from .col-md-6 {
    width: 100%;
}
}
</style>
<div class="container-fluid" id="pricing" style="background:#fff;">
	<div class="row fluid-row">
		<h2>Compelling media for every case!</h2>
		
		<div class="col-md-12 pricing-container">
			
			<form action="/buynow2" method="post" id="price-plan-from">
			@csrf
			<div class="row">
			<div class="col-md-6">
				<h2>Subscription Plan</h2>
				 <ul class="pricing-list">
			  
			  @foreach($response as $res)
			  @if($res->plan_purchase == 'M')
			  <li>
				<input type="radio" class="plisting" id="f-{{ $res->plan_id }}" name="packageid" value="{{$res->plan_id}}" required >
				<label for="f-{{ $res->plan_id }}">
				
				<p class="plan-name">{{ $res->plan_name }}</p>
				<p class="plan-descrption">{{ $res->plan_description }}</p>
		
				
				</label>
				<span for="f-price" class="plan-price">$ {!! $res->plan_price !!}</span>
				
				<div class="check"></div>
			  </li>
			  @endif
			  @endforeach
			  
			</ul>
			</div>
			<div class="col-md-6">
			<h2>Single Case Content Pack</h2>
				 <ul class="pricing-list">
			  
			  @foreach($response as $res)
			   @if($res->plan_purchase == 'O')
			  <li>
				<input type="radio" class="plisting" id="f-{{ $res->plan_id }}" name="packageid" value="{{$res->plan_id}}" required>
				<label for="f-{{ $res->plan_id }}" >
				<p class="plan-name">{{ $res->plan_name }}</p>
				<p class="plan-descrption">{{ $res->plan_description }}</p>
				</label>
				<span for="f-price" class="plan-price">$ {!! $res->plan_price !!}</span>
				
				<div class="check"></div>
			  </li>
			  @endif
			  @endforeach
			  
			</ul>
			</div>
			</div>
			 
			<button type="button" class="btn btn-primary org btn-setting">Buy now</button>
			</form>
			<div class="img-btm ">
					<span>Not quite what you are looking for?</span>
					<a href="/custom">Request Custom Graphics</a>
				</div>
		</div>
	</div>
</div>
<script>
$('.pricing-list li label').click(function() {
    $('.pricing-list li.active').removeClass('active');
    $(this).closest('li').addClass('active');
});
$('.btn-setting').on('click', function() { 
			var uniqueid=$("#uniqueid").val();
			if(uniqueid == ""){
				
				document.body.scrollTop = 0;
				document.documentElement.scrollTop = 0;
				openForm('signin');
				return false
			}
            output = $('input[name=packageid]:checked','#price-plan-from').val(); 
				if(output == undefined){
					alert("Please select Option");
					return false
				}
				$("#price-plan-from").submit();
        }); 
</script>

@include('footer')