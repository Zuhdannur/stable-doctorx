<div class="row row-deck">
    <div class="col-lg-12">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Tindakan</h3>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-sm table-striped">
                    <thead>
                        <th>#</th>
                        <th>Tindakan</th>
                        <th>Keterangan</th>
                    </thead>
                    <tbody>
                        @foreach ($treatment->detail as $k => $treatment)
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">{{ ($k+1) }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $treatment->name }}</span>
                            </td>
                            <td>
                                <span class="text-muted font-w600">{{ $treatment->description }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>