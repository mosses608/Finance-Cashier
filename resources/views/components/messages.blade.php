@if(session()->has('error_msg'))
<center>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  {{ session('error_msg') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif(session()->has('success_msg'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success_msg') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
</center>
@endif

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const alertMessage = document.querySelector('.alert');

    setTimeout(() => {
      alertMessage.style.display='none';
    }, 10000);
  });
</script>