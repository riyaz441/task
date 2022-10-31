<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>email</title>
</head>

<body>
    <form id="form">
        <div class="field">
            <label for="name">name</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="field">
            <label for="mobile">mobile</label>
            <input type="text" name="mobile" id="mobile">
        </div>
        <div class="field">
            <label for="comment">comment</label>
            <input type="text" name="comment" id="comment">
        </div>

        <input type="submit" id="button" value="Send Email">
    </form>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

    <script type="text/javascript">
        emailjs.init('V7sdpj1tpG6XkClhm')
    </script>
</body>
<script>
    const btn = document.getElementById('button');

    document.getElementById('form')
        .addEventListener('submit', function(event) {
            event.preventDefault();

            btn.value = 'Sending...';

            const serviceID = 'default_service';
            const templateID = 'template_sj26e8l';

            emailjs.sendForm(serviceID, templateID, this)
                .then(() => {
                    btn.value = 'Send Email';
                    alert('Sent!');
                }, (err) => {
                    btn.value = 'Send Email';
                    alert(JSON.stringify(err));
                });
        });
</script>

</html>