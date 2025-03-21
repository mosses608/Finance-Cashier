<div class="container-form" id="container-del-{{ $user->id }}" style="width: 50%; left: 25%;">
    <h3>Delete {{ $user->name }}</h3>
    <button type="button" class="close" onclick="closestForm(event, {{ $user->id }})">&times;</button>
    <br><hr>
    <form action="{{ route('delete.users') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>
    </form>
</div>

<script>
    window.deleteUserForm = function(event, userId){
        event.preventDefault();
        const updateForm = document.getElementById(`container-del-${userId}`);
        const darkScreen = document.querySelector('.transparent');

        updateForm.style.display='block';
        darkScreen.style.display='block';
    }

    window.closestForm = function(event, userId){
        event.preventDefault();
        const updateForm = document.getElementById(`container-del-${userId}`);
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