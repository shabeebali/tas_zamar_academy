<form action="{{route('contact')}}" method='post' enctype="multipart/form-data" name="contactus" id='contactus'
      accept-charset='UTF-8'>
    {{ csrf_field() }}
    <div><span class='error'></span></div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <span><input type="text" name="name" placeholder=" Name*" required value="{{old('name','')}}"></span>
        </div>
        <div class="form-group col-md-6">
            <div class="witr_form_field2">
                <span><input type="text" name="phone" placeholder="Phone*" required value="{{old('phone','')}}"></span>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <div class="witr_form_field2">
                <span><input type="email" name="email" placeholder="Email*" required value="{{old('email','')}}"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <span><textarea name="comment" placeholder="Comments/Message">{{old('comment','')}}</textarea></span>
    </div>
    <div class="form-group">
        <div class="g-recaptcha" data-sitekey="{{env('RECAPTCHA_SITE_KEY')}}"></div>
    </div>
    <p><button type="submit" class="btn btn-primary">Submit Now</button></p>
</form>
