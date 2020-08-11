@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="row">
            <div class="article col-12">
                @foreach($employees as $employee)
                    <div class="employee">
                        <h3>{{ $employee->full_name }}</h3>
                        <ul>
                            <li><strong> Birth date:</strong> {{ $employee->birth_date->format('d.m.Y') }}</li>
                            <li>
                                <strong> Department:</strong>
                                <a href="{{ route('employee-index', ['departmentId' => $employee->department->id] + request()->all()) }}">
                                    {{ $employee->department->name }}
                                </a>
                            </li>
                            <li><strong> Position:</strong> {{ $employee->position }} </li>
                            <li><strong> Employment type:</strong> {{ $employee->employment_type }} </li>
                            <li><strong> Month Salary:</strong> {{ $employee->getSalary() }} </li>
                        </ul>
                        @endforeach
                    </div>
            </div>

        </div>
        <div class="row col-12">
            <div class="col-2">
                <form action="{{ route('employee-index', request()->all()) }}" method="GET">
                    <select class="form-control" name="perPage" onchange="this.form.submit()">
                        <option {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
            <div class="col-10">
                {{ $employees->appends(request()->only(['departmentId', 'page', 'perPage']))->render() }}
            </div>
        </div>
    </div>
@endsection
