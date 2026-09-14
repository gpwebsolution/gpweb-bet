@extends('layouts.web')

@section('content')
    @include('includes.navbar_top')
    @include('includes.navbar_left')

    <div class="page__content">
        <div class="@if(\Helper::getCustomLayout()['expanded_layout']) container-fluid @else container @endif">
            @include('includes.wallet_card')

            <hr class="my-5" style="border-color:rgba(255,255,255,0.06);">

            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <h4 class="mb-0 fw-bold">
                    <i class="fa-regular fa-clock-rotate-left me-2"></i>HISTÓRICO DE JOGOS
                </h4>
            </div>

            <div class="filter-tabs mb-4">
                <span class="filter-tab @if(($filterDate ?? 'all') === 'all' && !$searchDate) active @endif" onclick="window.location.href='{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id={{ $filterGame }}&date=all'">Todas</span>
                <span class="filter-tab @if(($filterDate ?? 'all') === 'today') active @endif" onclick="window.location.href='{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id={{ $filterGame }}&date=today'">Hoje</span>
                <span class="filter-tab @if(($filterDate ?? 'all') === 'week') active @endif" onclick="window.location.href='{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id={{ $filterGame }}&date=week'">Esta Semana</span>
                <span class="filter-tab @if(($filterDate ?? 'all') === 'month') active @endif" onclick="window.location.href='{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id={{ $filterGame }}&date=month'">Este Mês</span>

                <div class="ms-auto d-flex gap-2 flex-wrap align-items-center">
                    <input type="date" class="form-control form-control-sm" style="width:auto;max-width:160px;background:rgba(255,255,255,0.04) !important;color:#fff !important;border:1px solid rgba(255,255,255,0.08) !important;" value="{{ $searchDate ?? '' }}" onchange="window.location.href='{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id={{ $filterGame }}&search_date='+this.value">
                    @if($searchDate)
                        <a href="{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id={{ $filterGame }}&date=all" class="filter-tab" style="padding:4px 10px;font-size:12px;"><i class="fa-regular fa-xmark"></i></a>
                    @endif
                    <select class="form-select form-select-sm" style="width:auto;max-width:120px;background:rgba(255,255,255,0.04) !important;color:#aaa !important;border:1px solid rgba(255,255,255,0.08) !important;" onchange="window.location.href='{{ route('panel.wallet.index') }}?type='+this.value+'&game_id={{ $filterGame }}&date={{ $filterDate }}&search_date={{ $searchDate }}'">
                        <option value="all" @if(($filterType ?? 'all') === 'all') selected @endif>Todos</option>
                        <option value="win" @if(($filterType ?? 'all') === 'win') selected @endif>Ganhos</option>
                        <option value="loss" @if(($filterType ?? 'all') === 'loss') selected @endif>Perdas</option>
                    </select>
                    <select class="form-select form-select-sm" style="width:auto;max-width:130px;" onchange="window.location.href='{{ route('panel.wallet.index') }}?type={{ $filterType }}&game_id='+this.value+'&date={{ $filterDate }}&search_date={{ $searchDate }}'">
                        <option value="all" @if(($filterGame ?? 'all') === 'all') selected @endif>Todos Jogos</option>
                        @foreach($games ?? [] as $game)
                            <option value="{{ $game->id }}" @if(($filterGame ?? 'all') == $game->id) selected @endif>{{ $game->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Jogo</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Aposta</th>
                                    <th scope="col">Ganho</th>
                                    <th scope="col">Lucro</th>
                                    <th scope="col">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <th scope="row">{{ $session->id }}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($session->game && $session->game->image)
                                                    <img src="{{ $session->game->image }}" alt="" class="game-icon">
                                                @endif
                                                {{ $session->game->name ?? 'Jogo #'.$session->game_id }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="type-badge @if($session->type === 'win') type-win @else type-loss @endif">
                                                {{ $session->type === 'win' ? 'Ganhou' : 'Perdeu' }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ \Helper::amountFormatDecimal($session->bet_amount) }}</td>
                                        <td class="fw-bold">{{ \Helper::amountFormatDecimal($session->result_amount) }}</td>
                                        <td class="fw-bold @if($session->profit >= 0) profit-positive @else profit-negative @endif">
                                            {{ $session->profit >= 0 ? '+' : '' }}{{ \Helper::amountFormatDecimal($session->profit) }}
                                        </td>
                                        <td>
                                            <small>{{ $session->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-5" colspan="7">
                                            <i class="fa-regular fa-clock-rotate-left text-muted" style="font-size:32px;"></i>
                                            <h5 class="text-muted mt-2">NENHUM JOGO ENCONTRADO</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-3 px-3 pb-3 d-flex justify-content-end">
                        {{ $sessions->appends(['type' => $filterType ?? 'all', 'game_id' => $filterGame ?? 'all', 'date' => $filterDate ?? 'all', 'search_date' => $searchDate ?? ''])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('/assets/js/jquery.mask.min.js') }}"></script>
@endpush
