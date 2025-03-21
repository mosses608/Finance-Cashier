<div class="container-form" id="container-m">
    <h3>{{ ('Store Registration') }}</h3>
    <button type="button" class="close" onclick="hideeForm(event)">&times;</button>
    <br><hr>
    <form action="{{ route('comp.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="input-field">
            <label for="">Store Name</label>
            <input type="text" name="store_name" id="" placeholder="Store Name">
        </div>

        <!-- Profile Image -->
        <div class="input-field">
            <label for="">Location</label>
            <input type="text" name="location" id="" placeholder="Location">
        </div>

        <div class="input-field">
            <label for="">Phone</label>
            <input type="tel" name="phone" id="" placeholder="Phone Number">
        </div>

        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>

    </form>
</div>

<script>
    function regyStoreForm(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-m");

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }

    function hideeForm(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-m");

        blurScreen.style.display='none';
        dataForm.style.display='none';
    }
</script>