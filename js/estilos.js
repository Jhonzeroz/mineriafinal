input[type="text"][required]:focus + label[placeholder]:before,
input[type="number"][required]:focus + label[placeholder]:before,
input[type="tel"][required]:focus + label[placeholder]:before,
select[required]:focus + label[placeholder]:before,
input[type="password"][required]:focus + label[placeholder]:before
{
  color: #3399CC;
}
input[type="text"][required]:focus + label[placeholder]:before,
input[type="tel"][required]:focus + label[placeholder]:before,
input[type="number"][required]:focus + label[placeholder]:before,
input[type="text"][required]:valid + label[placeholder]:before,
input[type="tel"][required]:valid + label[placeholder]:before,
input[type="number"][required]:valid + label[placeholder]:before,
select[required]:focus + label[placeholder]:before,
select[required]:valid + label[placeholder]:before,
input[type="password"][required]:focus + label[placeholder]:before,
input[type="password"][required]:valid + label[placeholder]:before
{
  -webkit-transition-duration: .2s;
  transition-duration: .2s;
  -webkit-transform: translate(0, -1.5em) scale(0.9, 0.9);
  transform: translate(0, -1.5em) scale(0.9, 0.9);
}
input[type="text"][required]:invalid + label[placeholder][alt]:before,
input[type="tel"][required]:invalid + label[placeholder][alt]:before,
input[type="number"][required]:invalid + label[placeholder][alt]:before,
select[required]:invalid + label[placeholder][alt]:before,
input[type="password"][required]:invalid + label[placeholder][alt]:before{
  content: attr(alt);
}
input[type="text"][required] + label[placeholder],
input[type="tel"][required] + label[placeholder],
input[type="number"][required] + label[placeholder],
select[required] + label[placeholder],
input[type="password"][required] + label[placeholder]{
  display: block;
  pointer-events: none;
  line-height: 1.25em;
  margin-top: calc(-1.5em - 2px);
  margin-bottom: calc((3em - 1em) + 2px);
  font-size: 0.85em;
  font-weight: normal;
}


input[type="text"][required] + label[placeholder]:before,
input[type="tel"][required] + label[placeholder]:before,
input[type="number"][required] + label[placeholder]:before,
select[required] + label[placeholder]:before,
input[type="password"][required] + label[placeholder]:before{
  content: attr(placeholder);
  display: inline-block;
  margin: 0;
  padding: 0 4px;
  color: #9C9C9C;
  white-space: nowrap;
  -webkit-transition: 0.3s ease-in-out;
  transition: 0.3s ease-in-out;
  background-image: -webkit-linear-gradient(top, #fff, #fff);
  background-image: linear-gradient(to bottom, #fff, #fff);
  background-size: 100% 5px;
  background-repeat: no-repeat;
  background-position: center;
}
.form-control {
  -webkit-appearance: normal;
  -moz-appearance: normal;
  appearance: normal;
  color: #31404d;
  box-shadow: none;
  border-radius: 0px;
  border:none;
  border-bottom: 1px solid #31404d;
}

.form-control:focus + .form-control-placeholder{
  -webkit-box-shadow:none;
  -moz-box-shadow:none;
  -o-box-shadow:none;
  box-shadow:none;
}

.form-control:focus{
  -webkit-box-shadow:none;
  box-shadow: none;
}
  position: relative;;
  z-index: -1;
  margin-top: -1.5em;

select[disabled='disabled']::-ms-value {
       color: #000;
}

input[disabled]{
     background: #D4D4D4;     
}


      .redondeado {
border-radius: 5px;
}


.content-wrapper, .contact-us {
 background: rgba(0, 0, 0, 0);
}