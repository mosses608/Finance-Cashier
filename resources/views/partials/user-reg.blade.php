<div class="container-form">
    <h3>{{ ('User Registration') }}</h3>
    <button type="button" class="close" onclick="closeForm(event)">&times;</button>
    <br><hr>
    <form action="{{ route('store.users') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="input-field">
            <label for="">Name</label>
            <input type="text" name="name" id="" placeholder="Name">
        </div>

        <!-- Role Id -->
        <div class="input-field">
            <label for="">Role</label>
            <select name="role_id" id="">
                <option value="" selected disabled>--select--</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->slug }}</option>
                @endforeach
            </select>
        </div>

        <!-- Email -->
        <div class="input-field">
            <label for="">Email</label>
            <input type="email" name="email" id="" placeholder="Email">
        </div>

        <!-- Phone -->
        <div class="input-field">
            <label for="">Phone</label>
            <input type="tel" name="phone" id="" placeholder="Phone">
        </div>

        <!-- Department -->
        <div class="input-field">
            <label for="">Department</label>
            <select name="department_id" id="">
                <option value="" selected disabled>--select--</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Username -->
        <div class="input-field">
            <label for="">Username</label>
            <input type="text" name="username" id="" placeholder="Username">
        </div>

        <!-- Password -->
        <div class="input-field">
            <label for="">Password</label>
            <input type="password" name="password" id="" placeholder="Password">
        </div>

        <div class="input-field">
            <label for="">Confirm Password</label>
            <input type="password" name="password_confirmation" id="" placeholder="Re-enter password">
        </div>

        <!-- Profile Image -->
        <div class="input-field">
            <label for="">Image</label>
            <input type="file" name="image" id="" accept="image/*">
        </div>

        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>

    </form>
</div>

<script>
    function popFormReg(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.querySelector('.container-form');

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }
</script>