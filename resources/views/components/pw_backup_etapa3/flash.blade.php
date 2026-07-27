<div class="space-y-3">
@if(session('success'))<x-pw.alert type="success">{{ session('success') }}</x-pw.alert>@endif
@if(session('error'))<x-pw.alert type="error">{{ session('error') }}</x-pw.alert>@endif
@if(session('warning'))<x-pw.alert type="warning">{{ session('warning') }}</x-pw.alert>@endif
@if($errors->any())<x-pw.alert type="error"><strong>Revise os campos informados.</strong><ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></x-pw.alert>@endif
</div>
