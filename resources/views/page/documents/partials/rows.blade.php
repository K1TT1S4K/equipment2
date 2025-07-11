@foreach ($documents as $key => $document)
    <tr class="text-center" style="cursor: pointer;" onclick="window.location='{{ route('document.edit', $document->id) }}'">
        <td onclick="event.stopPropagation();">
            <input type="checkbox" class="document-checkbox" name="selected_documents[]" value="{{ $document->id }}">
        </td>
        <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
        <td>{{ $document->document_type }}</td>
        @php
            $date = \Carbon\Carbon::parse($document->date);
            $updated = \Carbon\Carbon::parse($document->updated_at);
            $created = \Carbon\Carbon::parse($document->created_at);
        @endphp
        <td>{{ $date->format('j') }} {{ $date->locale('th')->translatedFormat('M') }} {{ $date->year + 543 }}</td>
        <td onclick="event.stopPropagation();">
            @if ($document->path)
                <a href="{{ asset('storage/' . $document->path) }}" download>{{ $document->path }}</a>
            @else
                -
            @endif
        </td>
        <td>{{ $updated->format('j') }} {{ $updated->locale('th')->translatedFormat('M') }} {{ $updated->year + 543 }}</td>
        <td>{{ $created->format('j') }} {{ $created->locale('th')->translatedFormat('M') }} {{ $created->year + 543 }}</td>
    </tr>
@endforeach

@if ($documents->isEmpty())
    <tr>
        <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
    </tr>
@endif
