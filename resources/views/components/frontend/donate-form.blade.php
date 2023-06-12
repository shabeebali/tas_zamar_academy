<form action="{{route('donate')}}" method='post' enctype="multipart/form-data" name="contactus" id='contactus'
      accept-charset='UTF-8'>
    @csrf
    <div><span class='error'></span></div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <span><input type="text" id="txtName" name="name" placeholder="Name*" required></span>
        </div>
        <div class="form-group col-md-6">
            <div class="witr_form_field2">
                <span><input type="email" id="txtEmail" name="email" placeholder="Email*" required></span>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <span><input type="text" id="txtLocation" name="location" placeholder="Location*" required></span>
        </div>
        <div class="form-group col-md-6">
            <span><input type="number" id="txtAmount" min="10" name="amount" placeholder="Enter Amount $"
                         required></span>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <div class="witr_form_field2">
                <span>
                    <select id="ddrDesignation" name="designation" required>
                       <!--<option value="" selected="" disabled="">--Select Designation--</option>-->
                       <option>General Donation</option>
                       <option>Operating Budget</option>
                       <option>Charitable Programs</option>
                    </select>
                </span>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <div class="witr_form_field2">
                <span><input type="text" id="txtAddress" name="address" placeholder="Address" required></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <span><textarea id="txtMessage" name="message" placeholder="Comments/Message"></textarea></span>
    </div>
    <div class="form-group text-left">
        <h3>Disclaimer:</h3>
        <h4>General Information:</h4>
        <p><input type="checkbox" required> Zamar Music Academy is a registered not-for-profit charitable organisation,
            Charitable Registration No. 749998290 RR 0001. By clicking submit, the donor understands that no goods or
            services were provided to them by Zamar Music Academy for their contribution. Donor hereby warrants that the
            donation is free from any and all encumbrances and that the donor has full legal right to make the donation.
        </p>
        <h4>No Revocation:</h4>
        <p><input type="checkbox" required> Donors may not revoke the donation and Donor understands that all donations
            made are final.</p>
        <h4>Expenses:</h4>
        <p><input type="checkbox" required> Any and all expenses associated with the execution of this donation, such as
            but not limited to expenses incurred during the transfer of this donation are the sole responsibility of the
            Donor</p>
    </div>
    <div class="form-group">
        <div class="g-recaptcha" data-sitekey="{{env('RECAPTCHA_SITE_KEY')}}"></div>
    </div>
    <p>
        <button type="submit" class="btn btn-primary">Submit Now</button>
    </p>
</form>
