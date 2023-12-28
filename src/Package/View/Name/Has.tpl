{{R3M}}
{{$options = options()}}
{{$has = Package.R3m.Io.Host:Configure:name.has($options)}}
{{if(!is.empty($has))}}
true
{{else}}
false
{{/if}}