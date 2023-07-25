<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br />
                    <label>Your email address</label>
                    <input id="email" placeholder="User Email" class="form-control" type="email" />
                    <br />
                    <button onclick="VerifyEmail()" class="btn w-100 float-end btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function VerifyEmail() {
    let email = document.getElementById('email').value;
    if (email.length === 0) {
        errorToast("Email Address Required!");
    } else {
        showLoader();
        let res = await axios.post("/send_otp", {
            email: email
        });
        hideLoader();
        if (res.status === 200) {
            sessionStorage.setItem('email', email);
            window.location.href = "/verifyOtp"
        } else {
            errorToast("Email Not Found")
        }

    }
}
</script>