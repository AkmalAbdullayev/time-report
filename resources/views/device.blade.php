@extends("layouts.master", ["title" => "Device"])

@section("content")
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\Illuminate\Support\Facades\Cache::get("users") as $k => $user)
                        @if ($user["UserInfoSearch"]["responseStatusStrg"] === "OK")
                            @foreach($user as $u)
                                <tr>
                                    @foreach ($u["UserInfo"] as $name)
                                        <td>{{$name["employeeNo"]}}</td>
                                        <td>{{$name["name"]}}</td>
{{--                                        <td>--}}
{{--                                            <div class="d-flex justify-content-between align-content-center">--}}
{{--                                                <div class="btn-group btn-group-sm mr-1">--}}
{{--                                                    <button--}}
{{--                                                        class="bx bxs-edit btn btn-outline-primary btn-sm"--}}
{{--                                                        id="editButton"--}}
{{--                                                        style="font-size:14px;"--}}
{{--                                                        wire:click="edit({{$employee->id}})"--}}
{{--                                                        data-toggle="modal"--}}
{{--                                                        data-target="#editModal"--}}
{{--                                                    >--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
