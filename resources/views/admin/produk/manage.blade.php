@extends('layouts.app')

@section('title', 'Blug | Manage Post')

@section('css')

@endsection

@section('content')
    <div class="ibox ">
        <div class="ibox-title">
            <h5>Manage Produk</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
            <form role="form"
                action="{{ !empty($produk) ? route('produk.update', ['produk' => $produk->id]) : route('produk.store') }}"
                id="post-form" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if (!empty($produk))
                    @method('PUT')
                @endif
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Upload Gambar Produk</label>
                    <input class="form-control" value="{{ old('gambar', @$produk->gambar) }}" type="file"
                        name="gambar">
                </div>
                <div class="form-group">
                    <label>Penjual/Toko</label>
                    <select class="form-control" name="id_penjual">
                        @foreach ($penjual as $item)
                            <option value="{{ $item->id }}"
                                {{ old('id_penjual', @$item->id == @$produk->id_penjual ? 'selected' : '') }}>
                                {{ $item->nama_toko }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select class="form-control" name="id_kategori">
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ old('id_kategori', @$item->id == @$produk->id_kategori ? 'selected' : '') }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" placeholder="Masukkan Nama Produk" value="{{ old('nama', @$produk->nama) }}" name="nama"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" min="1" placeholder="Masukkan Jumlah Produk" value="{{ old('jumlah', @$produk->jumlah) }}" name="jumlah"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" min="1" placeholder="Masukkan Harga Produk" value="{{ old('harga', @$produk->harga) }}" name="harga"
                        class="form-control">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="editor" name="deskripsi">{!! old('deskripsi', @$produk->deskripsi) !!}</textarea>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary w-100" type="submit"><strong>Simpan</strong></button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.tiny.cloud/1/bd8gdinu45n16lpavbs6chgxa381q91y62mhmx6uz40vn9yz/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.2.13/dist/semantic.min.js"></script>
    <script src="{{ asset('registered/multi-select/js/main.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak table wordcount',
            toolbar_mode: 'floating',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image',
            paste_data_images: true,
            height: 400,
            table_default_attributes: {
                border: '1'
            },
            file_picker_types: 'image',
            file_picker_callback: (cb, value, meta) => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];

                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        /*
                        Note: Now we need to register the blob in TinyMCEs image blob
                        registry. In the next release this part hopefully won't be
                        necessary, as we are looking to handle it internally.
                        */
                        const id = 'blobid' + (new Date()).getTime();
                        const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        const base64 = reader.result.split(',')[1];
                        const blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        /* call the callback and populate the Title field with the file name */
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    });
                    reader.readAsDataURL(file);
                });

                input.click();
            },
            content_style: '.left { text-align: left; } ' +
                'img.left, audio.left, video.left { float: left; } ' +
                'table.left { margin-left: 0px; margin-right: auto; } ' +
                '.right { text-align: right; } ' +
                'img.right, audio.right, video.right { float: right; } ' +
                'table.right { margin-left: auto; margin-right: 0px; } ' +
                '.center { text-align: center; } ' +
                'img.center, audio.center, video.center { display: block; margin: 0 auto; } ' +
                'table.center { margin: 0 auto; } ' +
                '.full { text-align: justify; } ' +
                'img.full, audio.full, video.full { display: block; margin: 0 auto; } ' +
                'table.full { margin: 0 auto; } ' +
                '.bold { font-weight: bold; } ' +
                '.italic { font-style: italic; } ' +
                '.underline { text-decoration: underline; } ' +
                '.example1 {} ' +
                'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }' +
                '.tablerow1 { background-color: #D3D3D3; }',
            formats: {
                alignleft: {
                    selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                    classes: 'left'
                },
                aligncenter: {
                    selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                    classes: 'center'
                },
                alignright: {
                    selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                    classes: 'right'
                },
                alignfull: {
                    selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                    classes: 'full'
                },
                bold: {
                    inline: 'span',
                    classes: 'bold'
                },
                italic: {
                    inline: 'span',
                    classes: 'italic'
                },
                underline: {
                    inline: 'span',
                    classes: 'underline',
                    exact: true
                },
                strikethrough: {
                    inline: 'del'
                },
                customformat: {
                    inline: 'span',
                    styles: {
                        color: '#00ff00',
                        fontSize: '20px'
                    },
                    attributes: {
                        title: 'My custom format'
                    },
                    classes: 'example1'
                }
            },
            style_formats: [{
                    title: 'Custom format',
                    format: 'customformat'
                },
                {
                    title: 'Align left',
                    format: 'alignleft'
                },
                {
                    title: 'Align center',
                    format: 'aligncenter'
                },
                {
                    title: 'Align right',
                    format: 'alignright'
                },
                {
                    title: 'Align full',
                    format: 'alignfull'
                },
                {
                    title: 'Bold text',
                    inline: 'strong'
                },
                {
                    title: 'Red text',
                    inline: 'span',
                    styles: {
                        color: '#ff0000'
                    }
                },
                {
                    title: 'Red header',
                    block: 'h1',
                    styles: {
                        color: '#ff0000'
                    }
                },
                {
                    title: 'Badge',
                    inline: 'span',
                    styles: {
                        display: 'inline-block',
                        border: '1px solid #2276d2',
                        'border-radius': '5px',
                        padding: '2px 5px',
                        margin: '0 2px',
                        color: '#2276d2'
                    }
                },
                {
                    title: 'Table row 1',
                    selector: 'tr',
                    classes: 'tablerow1'
                },
                {
                    title: 'Image formats'
                },
                {
                    title: 'Image Left',
                    selector: 'img',
                    styles: {
                        'float': 'left',
                        'margin': '0 10px 0 10px'
                    }
                },
                {
                    title: 'Image Right',
                    selector: 'img',
                    styles: {
                        'float': 'right',
                        'margin': '0 0 10px 10px'
                    }
                },
            ],
        });
    </script>
@endsection