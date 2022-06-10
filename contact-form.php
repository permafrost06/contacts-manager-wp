<body>
  <div class="container">
    <h1>Add Contact</h1>
    <form class="signup">

      <div class="input-outer">
        <div class="input-wrapper">
          <label class="input-label" for="contact_name">Name: </label>
          <div class="input-inner">
            <input class="target" type="text" id="contact_name">
          </div>
        </div>
      </div>

      <div class="input-outer">
        <div class="input-wrapper">
          <label class="input-label" for="contact_email">Email: </label>
          <div class="input-inner">
            <input type="email" id="contact_email">
          </div>
        </div>
      </div>

      <div class="input-outer">
        <div class="input-wrapper">
          <label class="input-label" for="contact_phone">Phone no: </label>
          <div class="input-inner">
            <input type="text" id="contact_phone">
          </div>
        </div>
      </div>

      <div class="input-outer">
        <div class="input-wrapper">

          <label class="input-label" for="contact_address">Address: </label>
          <div class="input-inner">
            <input type="text" id="contact_address">
          </div>
        </div>
      </div>
      <button>Submit</button>
      <p id="success-message"></p>
      <p id="error-message"></p>
    </form>
  </div>
</body>

</html>