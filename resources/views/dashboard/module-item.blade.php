<section class="data-group">
    <!-- GROUP HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2>
            {{ strtoupper($title ?? '') }}
        </h2>
    </div>

    <!-- TABLE CARD CONTAINER -->
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th class="number-column">#</th>
                    <th>ITEM</th>
                    <th>CATEGORY</th>
                    <th>SERVICES</th>
                    <th>
                        INTERNAL RATE
                        <br>
                        (MYR)
                    </th>
                    <th>REMARK</th>
                    <th>ACTION</th>
                </tr>
            </thead>

            <tbody class="data-body">
                {{-- 1. Display actual items retrieved from database --}}
                @forelse ($items as $index => $item)
                    <tr>
                        <td class="number-column">{{ $index + 1 }}</td>
                        <td>{{ $item['name'] ?? $item->name ?? '' }}</td>
                        <td>{{ $item['category_name'] ?? $item->category_name ?? 'N/A' }}</td>
                        <td>{{ $item['service'] ?? $item->service ?? 'N/A' }}</td>
                        <td>
                            @if(!empty($item['rate'] ?? $item->rate ?? null))
                                <span class="rate-badge">
                                    <span class="rate-value">{{  $item['rate'] ?? $item->rate }}</span>
                                    <span id="rateUnitDisplay">
                                        @if(isset($units))
                                            / {{ $units->firstWhere('unit_id', $item['unit_id'] ?? $item['unit_id'])->unit_name ?? '' }}
                                        @endif
                                    </span>
                                </span>
                            @endif
                        </td>
                        <td>{{ $item['description'] ?? $item->description ?? '' }}</td>
                        <td>
                            <div class="action-buttons">
                                <!-- EDIT BUTTON: Directs to item edit page with item ID -->
                                <a 
                                    href="{{ route('items.edit', $item['id'] ?? $item->id) }}" 
                                    class="action-button edit-button" 
                                    title="Edit"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <!-- DELETE FORM: Submits DELETE request to Controller -->
                                <form 
                                    action="{{ route('items.destroy', $item['id'] ?? $item->id) }}" 
                                    method="POST" 
                                    style="display: inline;" 
                                    onsubmit="return confirm('Are you sure you want to delete this item?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="action-button delete-button" 
                                        title="Delete"
                                    >
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- Rendered when no items exist in this group --}}
                @endforelse

                {{-- 2. Fill remaining empty rows for consistent table height --}}
                @php
                    $minRows = 2;
                    $filledRows = count($items ?? []);
                    $emptyRowsNeeded = max(0, $minRows - $filledRows);
                @endphp

                @for ($i = 0; $i < $emptyRowsNeeded; $i++)
                    <tr class="empty-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="action-button edit-button" title="Edit" disabled style="opacity: 0.3;">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="action-button delete-button" title="Delete" disabled style="opacity: 0.3;">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</section>