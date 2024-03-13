@extends('layouts.app')
@section('content')
    <form action="{{route('gcalendar.store')}}" method="POST" role="form">
        {{csrf_field()}}
        <legend>
            Create Event
        </legend>
        <div class="row">
            <div class="form-group col-6 mx-5 my-1">
                <label for="title">
                    Title
                </label>
                <input class="form-control" name="title" placeholder="Title" type="text">
            </div>
            <div class="form-group col-6 mx-5 my-1">
                <label for="description">
                    Description
                </label>
                <input class="form-control" name="description" placeholder="Description" type="text">
            </div>
            <div class="form-group col-6 mx-5 my-1>
                <label for="start_date">
                    Start Date
                </label>
                <input class="form-control" name="start_date" placeholder="Start Date" type="text">
            </div>
            <div class="form-group col-6 mx-5 my-1">
                <label for="end_date">
                    End Date
                </label>
                <input class="form-control" name="end_date" placeholder="End Date" type="text">
            </div>
            <div class="form-group col-6 mx-5 my-1">
                <button class="btn btn-primary" type="submit">
                    Submit
                </button>
            </div>
        </div>
    </form>
@endsection
