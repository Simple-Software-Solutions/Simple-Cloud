@extends('layouts.app')

@php
    use Spatie\MediaLibrary\Models\Media;

    $user = auth()->user();
    $user->videos->load('medias');
    $video_count = $user->videos->count();

    $file_size = $user->videos->sum(function($v){
        return $v->medias->sum('size');
    });

    $downloaded = Media::whereIn("parent_id",$user->videos->pluck("id"))->count();
@endphp

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('quickadmin.qa_dashboard')</div>
                <div class="panel-body">
                   @if(env("APP_MODE","personal") != "personal")
                    <div class="col-lg-4 col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px" class="fa fa-money fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9 ">
                                        <div class="huge">{{$user->balance}}</div>
                                        <div>Credit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px" class="fa fa-archive fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9 ">
                                        <div class="huge">{{$user->wallets->first()->address ?? "Generating ..."}}</div>
                                        <div>Deposit Address</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px" class="fa fa-cloud-upload fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9 ">
                                        <div class="huge">{{human_filesize($user->balance * 1024 * 1024 * 10 * ( $user->haveMasternode ? 2 : 1 ) )}}B</div>
                                        <div>Left Upload Limit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px" class="fa fa-cloud-upload fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9 ">
                                        <div class="huge">{{$video_count}}</div>
                                        <div>Total Uploaded Files</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px;" class="fa fa-hdd-o fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="huge">{{human_filesize($file_size)}}B</div>
                                        <div>Total Files Size</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px;" class="fa fa-hdd-o fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9">
                                    <div class="huge">{{$freeSize}}B / {{$totalSize}}B</div>
                                        <div>Server Disk Space</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px" class="fa fa-cloud-download fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="huge">{{$downloaded}}</div>
                                        <div>Total Download Files</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$user->haveMasternode)
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i style="font-size:45px" class="fa fa-globe fa-stack-2x"></i>
                                    </div>
                                    <div class="col-xs-9"  style="margin-bottom:10px">
                                    <form method="POST" action="{{route('admin.masternode.register')}}" class="form-inline" tyle="margin-top:5px">
                                            @csrf
                                            <div class="form-group">
                                                <input class="form-control" placeholder="PubKey Masternode" required name="data" type="text" id="data" />
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-default">Confirm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
