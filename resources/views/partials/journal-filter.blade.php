<div class="md4-filter">
    <center>
    <label for="">Date Range</label>
        <form action="{{ route('journals') }}" method="GET">
            @csrf
            <input type="date" name="fromDate" id="" autofocus="true">
            <input type="date" name="toDate" id="">
            <button type="submit" class="sbt"><i class="fa fa-search"></i></button>
        </form>
    </center>
</div>