<div class="md4-filter">
    <center>
    <label for="">Date Range</label>
        <form action="{{ route('trial.balance') }}" method="GET">
            @csrf
            <input type="date" name="fromDate" id="" autofocus="true">
            <input type="date" name="toDate" id="">
            <button type="submit" class="sbt"><i class="fa fa-search"></i></button>
        </form>
    </center>
</div>

<script>
    function filterTrialBal(event){
        event.preventDefault();
        const filterForm = document.querySelector('.md4-filter');

        filterForm.style.display='block';
        document.querySelector('.transparent').style.display='block';
    }

    function hidey(event){
        event.preventDefault();

        const filterForm = document.querySelector('.md4-filter');

        filterForm.style.display='none';
        document.querySelector('.transparent').style.display='none';
    }
</script>