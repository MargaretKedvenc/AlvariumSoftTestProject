@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="row">
            <div class="article col-9 blog-main">
                @foreach($employees as $employee)
                    <div class="employee">
                        <h3>{{ $employee->full_name }}</h3>
                        <ul>
                            <li><strong> Birth date:</strong> {{ $employee->birth_date->format('d.m.Y') }}</li>
                            <li><strong> Department:</strong> {{ $employee->department->name }}</li>
                            <li><strong> Position:</strong> {{ $employee->position }} </li>
                            <li><strong> Employment type:</strong> {{ $employee->employment_type }} </li>
                            <li><strong> Month Salary:</strong> {{ $employee->getSalary() }} </li>
                        </ul>
                        @endforeach
                    </div>
            </div>
        </div>
@endsection
