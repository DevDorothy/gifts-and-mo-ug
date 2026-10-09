@extends('admin.layout')
@section('title', 'Settings')

@section('content')
  <h1>Settings</h1>

  <div class="card">
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      @method('PUT')

      <label>Business Name</label>
      <input type="text" name="business_name" value="{{ old('business_name', $settings->business_name) }}" required>

      <label>WhatsApp Number <span style="font-weight:400;color:#888;">(digits only, with country code, e.g. 256700000000)</span></label>
      <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" required>

      <label>Call Number <span style="font-weight:400;color:#888;">(e.g. +256700000000)</span></label>
      <input type="text" name="call_number" value="{{ old('call_number', $settings->call_number) }}" required>

      <label>SMS Number <span style="font-weight:400;color:#888;">(e.g. +256700000000)</span></label>
      <input type="text" name="sms_number" value="{{ old('sms_number', $settings->sms_number) }}" required>

      <label>Site Base URL <span style="font-weight:400;color:#888;">(used for product links, QR codes, OG tags)</span></label>
      <input type="url" name="site_base_url" value="{{ old('site_base_url', $settings->site_base_url) }}" required>

      <label>Facebook Page URL</label>
      <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}">

      <label>Instagram URL</label>
      <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}">

      <label>TikTok URL</label>
      <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings->tiktok_url) }}">

      <label>X (Twitter) URL</label>
      <input type="url" name="x_url" value="{{ old('x_url', $settings->x_url) }}">

      <button type="submit" class="btn btn-primary" style="margin-top:20px;">Save Settings</button>
    </form>
  </div>
@endsection
