@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h1>Administrace</h1>

        <div class="row">
            <div class="col-sm-5">
                <table class="table table-bordered">
                    <tr>
                        <td>Verze zpěvníku</td>
                        <td><b>Generace #1 - v0.21 (17.2.2019)</b></td>
                    </tr>
                    <tr>
                        <td>Autor:</td>
                        <td>Miroslav Šerý, Michael Dojčár</td>
                    </tr>
                    <tr>
                        <td>Krizový telefon <br>(urgent. dotazy, závady)</td>
                        <td class="text-danger">+420 734 791 909</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-7">
                <h3>Info pro tým</h3>

                <div class="card">
                    <div class="card-header">Hlavní komunikační kanál</div>
                    <div class="card-body">Slack <a href="https://proscholy.slack.com">proscholy.slack.com</a> - lepší je stáhnout si app (mobil/PC)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
