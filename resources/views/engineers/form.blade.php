<div class="row">
    <div class="col-md-3">
        <label>الاسم الأول</label>
        <input type="text" name="first_name" value="{{ $engineer->first_name ?? '' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>اسم الأب</label>
        <input type="text" name="second_name" value="{{ $engineer->second_name ?? '' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>اسم الجد</label>
        <input type="text" name="third_name" value="{{ $engineer->third_name ?? '' }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>اسم العائلة</label>
        <input type="text" name="last_name" value="{{ $engineer->last_name ?? '' }}" class="form-control">
    </div>
</div>