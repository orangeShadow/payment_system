<h1>Report for {{$user->name}}</h1>
<table style="width:100%">
    <thead>
        <tr>
            <td>From</td>
            <td>To</td>
            <td>AmountFrom</td>
            <td>AmountTo</td>
            <td>Date</td>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>@if(!empty($payment->purse_from)){{$payment->purseFrom->user->name}}@endif</td>
                <td>{{$payment->purseTo->user->name}}</td>
                <td>{{$payment->amount_from}} @if(!empty($payment->purse_from)) {{$payment->purseFrom->getCurrency()}} @endif</td>
                <td>{{$payment->amount_to}} {{$payment->purseTo->getCurrency()}}</td>
                <td>{{$payment->created_at}}</td>
            </tr>
        @endforeach
    </tbody>
    @if(count($total)>0)
        @php $total = $total[0]; @endphp
        <tfooter>
            <tr>
                <td colspan="2">Тоtal</td>
                <td>
                    {{$total->totalFrom}} {{$user->purse->getCurrency()}}<br>
                    {{$total->totalFromUsd}} $
                </td>
                <td>
                    {{$total->totalTo}} {{$user->purse->getCurrency()}}<br>
                    {{$total->totalToUsd}} $
                </td>
                <td></td>
            </tr>
        </tfooter>
    @endif
</table>

