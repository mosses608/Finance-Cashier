<div class="container-form" id="container-form-{{ $user->id }}" style="width: 50%; left: 25%;">
    <h3>{{ ('Update User') }}</h3>
    <button type="button" class="close" onclick="closetForm(event, {{ $user->id }})">&times;</button>
    <br><hr>
    <form action="{{ route('update.users') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Name -->
        <div class="input-field" style="width: 49%;">
            <label for="">Name</label>
            <input type="text" name="name" id="" value="{{ $user->name }}">
        </div>

        <!-- Role Id -->
        <div class="input-field" style="width: 49%;">
            <label for="">Role</label>
            <select name="role_id" id="">
                <option value="{{ $user->role_id }}">{{ $user->role_id }}</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->slug }}</option>
                @endforeach
            </select>
        </div>

        <!-- Email -->
        <div class="input-field" style="width: 49%;">
            <label for="">Email</label>
            <input type="email" name="email" id="" value="{{ $user->email }}">
        </div>

        <!-- Phone -->
        <div class="input-field" style="width: 49%;">
            <label for="">Phone</label>
            <input type="tel" name="phone" id="" value="{{ $user->phone }}">
        </div>

        <!-- Department -->
        <div class="input-field" style="width: 49%;">
            <label for="">Department</label>
            <select name="department_id" id="">
                <option value="{{ $user->department_id }}">{{ $user->department_id }}</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Username -->
        <div class="input-field" style="width: 49%;">
            <label for="">Username</label>
            <input type="text" name="username" id="" value="{{ $user->username }}">
        </div>

        <!-- Profile Image -->
        <div class="input-field" style="width: 100%;">
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

    window.editUserForm = function(event, userId){
        event.preventDefault();
        const updateForm = document.getElementById(`container-form-${userId}`);
        const darkScreen = document.querySelector('.transparent');

        updateForm.style.display='block';
        darkScreen.style.display='block';
    }

    window.closetForm = function(event, userId){
        event.preventDefault();
        const updateForm = document.getElementById(`container-form-${userId}`);
        const darkScreen = document.querySelector('.transparent');
        const container = document.querySelector('.container-form');

        updateForm.style.display='none';
        darkScreen.style.display='none';
        container.style.display='none';
    }
</script>

<style>
    .input-field{
        width: 48%;
    }

    .input-field label{
        display: flex !important;
    }
</style>