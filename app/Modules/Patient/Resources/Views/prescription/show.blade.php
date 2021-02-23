@extends('backend.layouts.app')

@section('title', __('patient::labels.prescription.management') . ' | ' . __('patient::labels.prescription.create'))

@section('content')
@include('patient::prescription.partials.hasil-diagnosa')
@endsection