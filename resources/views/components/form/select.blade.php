@props(['label','name','options'=>[],'value'=>'','required'=>false,'placeholder'=>'Selecione'])
<x-pw.select :label="$label" :name="$name" :value="$value" :placeholder="$placeholder" :required="$required">
@foreach($options as $optionValue=>$optionLabel)<option value="{{ $optionValue }}" @selected((string)old($name,$value)===(string)$optionValue)>{{ $optionLabel }}</option>@endforeach
</x-pw.select>
