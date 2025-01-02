<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
                <table id="" class="table rounded-corners table-bordered">
                  <thead>
                  <tr>
				
					<th>Zone</th>
					<th>Active Agents</th>
					<th>No. of Bkgs.</th>
					<th>No. of  Services</th>
					<th>Accounted Profit</th>
					<th>UnAccounted Sales</th>
					<th>Total Sales</th>
					<th>Total Cost</th>
					<th>Ticket Sales</th>
					<th>Ticket Cost</th>
					<th>Transfer Sales</th>
					<th>Transfer Cost</th>
					<th>Hotel Sales</th>
					<th>Hotel  Cost</th>
					<th>Hotel - Profit</th>
				  </tr>
				  
                  </thead>
                  <tbody>
				  @foreach ($records as $k => $record)
				 
                  <tr class="">
					<td>{{$k}}</td>
					<td>{{$record['activeAgents']}}</td>
					<td>{{$record['no_ofBkgs']}}</td>
					<td>{{$record['no_ofServices']}}</td>
					<td>@if($record['PL'] > 0) <span style="color: white;font-weight:bold;background-color: green;padding: 8px;display: inline-block;width: 100%;">{{ @$record['PL']}}</span> @elseif($record['PL'] < 0) <span style="color: white;font-weight:bold;background-color: red;padding: 8px;display: inline-block;width: 100%;">{{ @$record['PL']}} </span>@else 0 @endif</td>
					<td>{{$record['unAccountedSales']}}</td>
					<td>{{$record['totalSales']}}</td>
					<td>{{$record['totalCost']}}</td>
					<td>{{$record['totalTicketSPAfterDiscount']}}</td>
					<td>{{$record['totalTicketCost']}}</td>
					<td>{{$record['totalTransferSPAfterDiscount']}}</td>
					<td>{{$record['totalTransferCost']}}</td>
					<td>{{$record['totalHotelSP']}}</td>
					<td>{{$record['totalHotelCost']}}</td>
					<td>@if($record['PLHotel'] > 0) <span style="color: white;font-weight:bold;background-color: green;padding: 8px;display: inline-block;width: 100%;">{{ @$record['PLHotel']}}</span> @elseif($record['PLHotel'] < 0) <span style="color: white;font-weight:bold;background-color: red;padding: 8px;display: inline-block;width: 100%;">{{ @$record['PLHotel']}} </span>@else 0 @endif</td>
                  @endforeach
                  </tbody>
                </table>
				</body>
</html>