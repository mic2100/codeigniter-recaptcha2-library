CodeIgniter Recaptcha2 Library
=========================

This is a lightweight library for use with the CodeIgniter 2.x/3.x framework. It will provide an easy interface to generate and verify recaptcha2 (the I'm not a robot tickbox).

Installation
--------

This is very easy, just copy the files to their appropriate folders.

```
config/recaptcha2.php -> application/config/recaptcha2.php
libraries/Recaptcha2.php -> application/libraries/Recaptcha2.php
```

Usage
----

Controller Example:
```lang="php"
class Contact extends CI_Controller
{
    public function index()
    {
        $this->config->load('recaptcha2');
        $data['siteKey'] = $this->config->item('recaptcha2-site-key');

        if ($this->input->post()) {
            $this->form_validation->set_rules(
                'g-recaptcha-response',
                'recaptcha code',
                'callback__check_recaptcha'
            );

            if ($this->form_validation->run()) {
                //success
            }

            //failed
        }
    }

    public function _check_captcha($response)
    {
        $this->load->library('Recaptcha2');

        if (!$this->recaptcha2->verify($response)) {
            $this->form_validation->set_message('_check_captcha', 'You must complete the recaptcha code');
            return false;
        }
        return true;
    }
}
```

Form Example:
```
<form>
    <em>Please tick the box below to prove you are a human.</em>
    <div id="recaptcha-field" class="g-recaptcha"></div>
</form>
<!-- this is need to load the recaptcha -->
<script>
    var CaptchaCallback = function () {
        grecaptcha.render('recaptcha-field', {'sitekey' : '<?= $siteKey ?>'});
    };
</script>
```

Config:
You can register for your site and secret keys here https://www.google.com/recaptcha/admin
```lang="php"
$config['recaptcha2-site-key'] = '<enter your site key here>';
$config['recaptcha2-secret-key'] = '<enter your secret key here>';
$config['recaptcha2-verify-url'] = 'https://www.google.com/recaptcha/api/siteverify';
```
