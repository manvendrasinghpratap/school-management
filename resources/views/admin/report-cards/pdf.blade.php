<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Report Card -
        {{ $reportCard->student->student_number ?? 'Student' }}
    </title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.4;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .school-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .document-title {
            font-size: 17px;
            font-weight: bold;
            margin-top: 8px;
        }

        .document-subtitle {
            font-size: 11px;
            color: #555;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            background: #eeeeee;
            border: 1px solid #cccccc;
            padding: 7px;
            margin-top: 15px;
            margin-bottom: 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #cccccc;
            padding: 7px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 25%;
            background: #f7f7f7;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #999999;
            padding: 7px;
        }

        .marks-table th {
            background: #eeeeee;
            font-weight: bold;
            text-align: center;
        }

        .marks-table td.center {
            text-align: center;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .summary-table td {
            border: 1px solid #cccccc;
            padding: 8px;
        }

        .summary-label {
            width: 30%;
            font-weight: bold;
            background: #f7f7f7;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            font-weight: bold;
        }

        .footer {
            margin-top: 35px;
            border-top: 1px solid #cccccc;
            padding-top: 10px;
            font-size: 10px;
            color: #666666;
        }

        .signature-table {
            width: 100%;
            margin-top: 45px;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding-top: 35px;
        }

        .signature-line {
            border-top: 1px solid #333333;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">

        <div class="school-name">
            {{ $reportCard->school->name ?? 'School Management System' }}
        </div>

        <div class="document-title">
            STUDENT REPORT CARD
        </div>

        <div class="document-subtitle">
            Academic Examination Result
        </div>

    </div>


    {{-- Student Information --}}
    <div class="section-title">
        Student Information
    </div>

    <table class="info-table">

        <tr>

            <td class="label">
                Student Name
            </td>

            <td>
                {{ trim(
                    ($reportCard->student->first_name ?? '') . ' ' .
                    ($reportCard->student->middle_name ?? '') . ' ' .
                    ($reportCard->student->last_name ?? '')
                ) ?: '—' }}
            </td>

            <td class="label">
                Student Number
            </td>

            <td>
                {{ $reportCard->student->student_number ?? '—' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Admission Number
            </td>

            <td>
                {{ $reportCard->student->admission_number ?? '—' }}
            </td>

            <td class="label">
                Gender
            </td>

            <td>
                {{ ucfirst($reportCard->student->gender ?? '—') }}
            </td>

        </tr>

    </table>


    {{-- Examination Information --}}
    <div class="section-title">
        Examination Information
    </div>

    <table class="info-table">

        <tr>

            <td class="label">
                Examination
            </td>

            <td>
                {{ $reportCard->examination->name ?? '—' }}
            </td>

            <td class="label">
                Examination Type
            </td>

            <td>
                {{ ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $reportCard->examination->type ?? '—'
                    )
                ) }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Start Date
            </td>

            <td>
                @if($reportCard->examination?->start_date)
                    {{ \Carbon\Carbon::parse(
                        $reportCard->examination->start_date
                    )->format('d M Y') }}
                @else
                    —
                @endif
            </td>

            <td class="label">
                End Date
            </td>

            <td>
                @if($reportCard->examination?->end_date)
                    {{ \Carbon\Carbon::parse(
                        $reportCard->examination->end_date
                    )->format('d M Y') }}
                @else
                    —
                @endif
            </td>

        </tr>

    </table>


    {{-- Subject Marks --}}
    <div class="section-title">
        Subject Performance
    </div>

    <table class="marks-table">

        <thead>

            <tr>

                <th style="width: 7%;">
                    #
                </th>

                <th style="width: 40%;">
                    Subject
                </th>

                <th style="width: 18%;">
                    Score
                </th>

                <th style="width: 18%;">
                    Maximum
                </th>

                <th style="width: 17%;">
                    Grade
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($marks as $mark)

                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $mark->course->name ?? '—' }}
                    </td>

                    <td class="center">
                        {{ number_format((float) $mark->score, 2) }}
                    </td>

                    <td class="center">
                        {{ number_format((float) $mark->maximum_score, 2) }}
                    </td>

                    <td class="center">
                        {{ $mark->grade ?? '—' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="text-center"
                    >
                        No approved subject marks found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- Result Summary --}}
    <div class="section-title">
        Result Summary
    </div>

    <table class="summary-table">

        <tr>

            <td class="summary-label">
                Total Score
            </td>

            <td>
                {{ $result
                    ? number_format((float) $result->total_score, 2)
                    : '—'
                }}
            </td>

        </tr>

        <tr>

            <td class="summary-label">
                Average
            </td>

            <td>
                {{ $result
                    ? number_format((float) $result->average, 2)
                    : '—'
                }}
            </td>

        </tr>

        <tr>

            <td class="summary-label">
                Overall Grade
            </td>

            <td>
                {{ $result->grade ?? '—' }}
            </td>

        </tr>

        <tr>

            <td class="summary-label">
                Position
            </td>

            <td>
                {{ $result->position ?? '—' }}
            </td>

        </tr>

    </table>


    {{-- Signatures --}}
    <table class="signature-table">

        <tr>

            <td>
                <div class="signature-line">
                    Class Teacher
                </div>
            </td>

            <td>
                <div class="signature-line">
                    Examination Officer
                </div>
            </td>

            <td>
                <div class="signature-line">
                    Principal
                </div>
            </td>

        </tr>

    </table>


    {{-- Footer --}}
    <div class="footer">

        <div>
            Generated by:
            {{ $reportCard->generatedBy->name ?? 'System' }}
        </div>

        <div>
            Generated on:
            {{ $reportCard->created_at?->format('d M Y H:i') ?? now()->format('d M Y H:i') }}
        </div>

        <div>
            This document is an official school report card.
        </div>

    </div>

</body>
</html>