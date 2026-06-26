@extends('errors.docs.layout')


@section('title', __('Error'))
@section('code', $exception->getStatusCode())
@section('message', __($exception->getMessage() ?: 'Something went wrong!'))
