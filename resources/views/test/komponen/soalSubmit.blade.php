{{-- <div class="container"> --}}
    <ul class="navbar nav ms-auto">
        <li class="nav-item">
            {{-- <span id="answered">0</span>/<span id="total">{{ $soal->amount }}</span> Soal Terjawab --}}
            <span id="answered">0</span>/<span id="total">1</span> Soal Terjawab
        </li>
        <li class="nav-item ms-3">
            <a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
        </li>
        <li class="nav-item ms-3">
            <button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
        </li>
    </ul>
{{-- </div> --}}