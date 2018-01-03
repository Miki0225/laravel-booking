@extends('layout.base')

@section('content')
<h1>Boeking</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(isset($booking))
  <form action="{{ route('booking.edit', $booking->id) }}" method="POST">
@else
  <form action="{{ route('booking.create') }}" method="POST">
@endif
  {{ csrf_field() }}
  <fieldset>
  <div class="row justify-content-md-center">
    <div class="col-6">
      <div class="form-group">
        <label for="arrivalInput">Aankomst</label>
        <div class="input-group date">
          <input type="date" class="form-control actual_range" name="arrival" id="arrivalInput" autocomplete="off" required
            @if(isset($booking)) value="{{ $booking->arrival->format('d-m-Y') }}"
            @elseif(isset($date)) value="{{ $date->format('d-m-Y') }}"
            @endif>
          <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
        </div>
      </div>
      <div class="form-group">
        <label for="departureInput">Vertrek</label>
        <div class="input-group date">
          <input type="date" class="form-control actual_range" name="departure" id="departureInput" autocomplete="off" required
            @if(isset($booking)) value="{{ $booking->departure->format('d-m-Y') }}"
            @elseif(isset($date)) value="{{ $date->addWeek()->format('d-m-Y') }}"
            @endif>
          <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
        </div>
      </div>
      <div class="form-group">
        <label for="customerSelect">Hoofdboeker</label>
        <select class="form-control" name="customer" id="customerSelect" placeholder="Selecteer gast...">
          <option></option>
          <option value="new-guest">Nieuwe gast...</option>
          @foreach($guests as $guest)
            <option @if(isset($booking) && $booking->customer_id == $guest->id) selected @endif value="{{ $guest->id }}">{{ $guest->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="guestsSelect"># gasten</label>
        <select class="form-control" name="guests" id="guestsSelect">
          @for($i=0; $i<$max_beds; $i++)
            <option @if(isset($booking) && $booking->guests == ($i+1)) selected @endif>{{ $i+1 }}</option>
          @endfor
        </select>
      </div>
    </div>
    <div class="col-6">
      <div class="form-group">
        <label for="roomSelect">Kamer</label>
        <select class="form-control" name="room" id="roomSelect">
          @foreach($rooms as $room)
            <option
            @if(isset($booking) && $booking->rooms[0]->id == $room->id) selected @endif
            @if(isset($room_id) && $room_id == $room->id) selected @endif
            value="{{ $room->id }}">{{ $room->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="basePriceInput">Basis prijs</label>
        <div class="input-group">
          <span class="input-group-addon">€</span>
          <input class="form-control" name="basePrice" id="basePriceInput" autocomplete="off" type="number" required @if(isset($booking)) value="{{ $booking->basePrice }}" @else value="0" @endif min="0">
        </div>
      </div>
      <div class="form-group">
        <label for="discountInput">Korting</label>
        <div class="input-group">
          <span class="input-group-addon">%</span>
          <input class="form-control" name="discount" id="discountInput" autocomplete="off" type="number" required @if(isset($booking)) value="{{ $booking->discount }}" @else value="0" @endif min="0" max="100">
        </div>
      </div>
      <div class="form-group">
        <label for="depositInput">Voorschot</label>
        <div class="input-group">
          <span class="input-group-addon">€</span>
          <input class="form-control" name="deposit" id="depositInput" autocomplete="off" type="number" required @if(isset($booking)) value="{{ $booking->deposit }}" @else value="0" @endif min="0">
        </div>
      </div>
      <div class="form-group">
        <label class="form-check-label">
          <input class="form-check-input" name="ext_booking" type="checkbox" value="yes" @if(isset($booking) && $booking->ext_booking) checked @endif>
          Externe boeking? (booking.com,&hellip;)
        </label>
      </div>
    </div>
  </div>
  <div class="row justify-content-md-center">
    <div class="col-12">
      <div class="form-group">
        <label for="commentTextArea">Opmerkingen</label>
        <textarea class="form-control" name="comments" id="commentTextArea" rows="5">@if(isset($booking)){{ $booking->comments }}@endif</textarea>
      </div>
    </div>
  </div>
    @if(isset($booking))
      <a href="{{ route('booking.delete', $booking) }}" class="btn btn-danger">Verwijder boeking</a>
      <button type="submit" class="btn btn-primary float-right">Opslaan!</button>
    @else
      <button type="submit" class="btn btn-primary float-right">Voeg toe!</button>
    @endif
  </fieldset>
</form>

<div class="modal" id="newGuestModal" tabindex="-1" role="dialog" aria-labelledby="newGuestModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nieuwe gast</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @php unset($guest) @endphp
        @include('guests.create_form')
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
        <button class="btn btn-primary" id="saveGuest">Opslaan</button>
      </div>
    </div>
  </div>
</div>

@endsection