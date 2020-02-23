@extends('errors::layout')

@section('title', __('Prohibido'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Usted no tiene permiso para acceder a este servidor'))
