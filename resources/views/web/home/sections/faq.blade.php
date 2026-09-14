<div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                Qual é o valor mínimo para depósito?
            </button>
        </h2>
        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
            @php $prefix = config('setting')['prefix'] ?? 'R$'; @endphp
            <div class="accordion-body">
                Na nossa plataforma, o valor mínimo de depósito é de <strong>{{ $prefix }} {{ number_format(config('setting')['min_deposit'] ?? 10, 0, ',', '.') }}</strong>.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                Qual é o valor mínimo para saque?
            </button>
        </h2>
        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
                Na nossa plataforma, o valor mínimo de saque é de {{ $prefix }} {{ number_format(config('setting')['min_saque'] ?? 10, 0, ',', '.') }}, sem limite máximo estabelecido.
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                Quais são as regras do programa de CPA (Custo por Aquisição)?
            </button>
        </h2>
        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
            <div class="accordion-body">
                <p>No nosso programa de CPA, as comissões são definidas da seguinte forma:</p>

                <br>
                @php
                    $cpa = (int) (config('setting')['affiliate_default_cpa'] ?? 40);
                    $baseline = (int) (config('setting')['affiliate_default_baseline'] ?? 70);
                @endphp
                <p><i class="fa-solid fa-check mr-2"></i> Para perfis de grande alcance, a comissão é de {{ $prefix }} {{ number_format($cpa, 0, ',', '.') }}, com um valor de Baseline de {{ $prefix }} {{ number_format($baseline, 0, ',', '.') }}.</p>
                <p><i class="fa-solid fa-check mr-2"></i> Para outros perfis, o CPA é de {{ $prefix }} {{ number_format(max(10, intdiv($cpa, 2)), 0, ',', '.') }}, com um valor de Baseline de {{ $prefix }} {{ number_format(max(20, intdiv($baseline, 2)), 0, ',', '.') }}.</p>
            </div>
        </div>
    </div>
</div>
