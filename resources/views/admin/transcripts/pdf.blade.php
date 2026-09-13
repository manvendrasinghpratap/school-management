<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Academic Transcript -
        {{ $student->first_name ?? '' }}
        {{ $student->last_name ?? '' }}
    </title>

    <style>

        @page {
            margin: 35px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.45;
        }

        .text-center {
            text-align: center;
        }

        .school-header {
            text-align: center;
            margin-bottom: 18px;
        }

        .school-name {
            font-size: 21px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .school-details {
            font-size: 10px;
            color: #555;
        }

        .document-title {
            margin-top: 14px;
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .document-subtitle {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #999;
        }

        .student-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .student-info td {
            padding: 5px 7px;
            vertical-align: top;
            border: 1px solid #ddd;
        }

        .student-info .label {
            width: 18%;
            font-weight: bold;
            background: #f3f3f3;
        }

        .student-info .value {
            width: 32%;
        }

        .academic-section {
            margin-top: 18px;
        }

        .academic-heading {
            font-size: 12px;
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 5px;
            padding: 6px 8px;
            background: #f3f3f3;
            border: 1px solid #ccc;
        }

        .academic-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }

        .academic-meta td {
            border: 1px solid #ddd;
            padding: 5px 7px;
        }

        .academic-meta .label {
            width: 18%;
            background: #f5f5f5;
            font-weight: bold;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .results-table th {
            background: #e9ecef;
            font-weight: bold;
            text-align: center;
            padding: 6px 5px;
            border: 1px solid #aaa;
            font-size: 9px;
        }

        .results-table td {
            padding: 5px;
            border: 1px solid #bbb;
            font-size: 9px;
        }

        .results-table .center {
            text-align: center;
        }

        .results-table .right {
            text-align: right;
        }

        .pass {
            font-weight: bold;
        }

        .fail {
            font-weight: bold;
        }

        .result-summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 7px;
            margin-bottom: 14px;
        }

        .result-summary td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }

        .result-summary .label {
            width: 25%;
            background: #f5f5f5;
            font-weight: bold;
        }

        .no-results {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            color: #777;
        }

        .signature-area {
            width: 100%;
            margin-top: 45px;
        }

        .signature-area td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 15px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 35px;
            padding-top: 5px;
            font-size: 9px;
        }

        .footer {
            position: fixed;
            bottom: -18px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #777;
        }

        .page-number:after {
            content: counter(page);
        }

    </style>

</head>


<body>

    {{-- ========================================================= --}}
    {{-- SCHOOL HEADER                                             --}}
    {{-- ========================================================= --}}

    <div class="school-header">

        <div class="school-name">
            {{ $school->name ?? 'School' }}
        </div>

        @if(!empty($school->address))

            <div class="school-details">
                {{ $school->address }}
            </div>

        @endif


        @if(!empty($school->phone) || !empty($school->email))

            <div class="school-details">

                @if(!empty($school->phone))

                    Phone: {{ $school->phone }}

                @endif


                @if(!empty($school->phone) && !empty($school->email))

                    &nbsp; | &nbsp;

                @endif


                @if(!empty($school->email))

                    Email: {{ $school->email }}

                @endif

            </div>

        @endif


        <div class="document-title">
            Official Academic Transcript
        </div>


        <div class="document-subtitle">
            Complete Academic Record
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STUDENT INFORMATION                                       --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Student Information
    </div>


    @php

        $studentName = trim(
            ($student->first_name ?? '') . ' ' .
            ($student->middle_name ?? '') . ' ' .
            ($student->last_name ?? '')
        );

    @endphp


    <table class="student-info">

        <tr>

            <td class="label">
                Student Name
            </td>

            <td class="value">
                {{ $studentName ?: '—' }}
            </td>


            <td class="label">
                Student Number
            </td>

            <td class="value">
                {{ $student->student_number ?? '—' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Admission Number
            </td>

            <td class="value">
                {{ $student->admission_number ?? '—' }}
            </td>


            <td class="label">
                Gender
            </td>

            <td class="value">
                {{ $student->gender ?? '—' }}
            </td>

        </tr>


        @if(!empty($student->date_of_birth))

            <tr>

                <td class="label">
                    Date of Birth
                </td>

                <td class="value">

                    {{ \Carbon\Carbon::parse(
                        $student->date_of_birth
                    )->format('d M Y') }}

                </td>


                <td class="label">
                    Status
                </td>

                <td class="value">
                    {{ ucfirst($student->status ?? 'Active') }}
                </td>

            </tr>

        @endif

    </table>


    {{-- ========================================================= --}}
    {{-- ACADEMIC RECORD                                           --}}
    {{-- ========================================================= --}}

    <div class="section-title">
        Academic Record
    </div>


    @if($transcriptData->count())

        @foreach($transcriptData as $academicRecord)

            @php

                $result = $academicRecord['result'];

                $academicMarks = $academicRecord['marks'];

                $examination = $result->examination;

            @endphp


            <div class="academic-section">


                {{-- Examination Heading --}}
                <div class="academic-heading">

                    {{ $examination?->name ?? 'Examination' }}

                    @if($examination?->type)

                        —
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $examination->type
                            )
                        ) }}

                    @endif

                </div>


                {{-- Examination Information --}}
                <table class="academic-meta">

                    <tr>

                        <td class="label">
                            Examination
                        </td>

                        <td>
                            {{ $examination?->name ?? '—' }}
                        </td>


                        <td class="label">
                            Type
                        </td>

                        <td>

                            @if($examination?->type)

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $examination->type
                                    )
                                ) }}

                            @else

                                —

                            @endif

                        </td>

                    </tr>


                    @if(
                        $examination?->start_date ||
                        $examination?->end_date
                    )

                        <tr>

                            <td class="label">
                                Start Date
                            </td>

                            <td>

                                @if($examination?->start_date)

                                    {{ \Carbon\Carbon::parse(
                                        $examination->start_date
                                    )->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            <td class="label">
                                End Date
                            </td>

                            <td>

                                @if($examination?->end_date)

                                    {{ \Carbon\Carbon::parse(
                                        $examination->end_date
                                    )->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </td>

                        </tr>

                    @endif

                </table>


                {{-- ================================================= --}}
                {{-- SUBJECT RESULTS                                   --}}
                {{-- ================================================= --}}

                <table class="results-table">

                    <thead>

                        <tr>

                            <th style="width: 5%;">
                                #
                            </th>

                            <th style="width: 32%;">
                                Subject
                            </th>

                            <th style="width: 14%;">
                                Score
                            </th>

                            <th style="width: 14%;">
                                Maximum
                            </th>

                            <th style="width: 14%;">
                                Percentage
                            </th>

                            <th style="width: 10%;">
                                Grade
                            </th>

                            <th style="width: 11%;">
                                Result
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($academicMarks as $mark)

                            @php

                                $score = (float) $mark->score;

                                $maximum = (float) $mark->maximum_score;

                                $percentage = $maximum > 0
                                    ? ($score / $maximum) * 100
                                    : 0;


                                /*
                                |--------------------------------------------------------------------------
                                | Resolve Grade From Configured School Grading Scale
                                |--------------------------------------------------------------------------
                                |
                                | The percentage is used to determine the correct
                                | configured grade range.
                                |
                                */

                                $configuredGrade = \App\Models\Grade::query()
                                    ->where('school_id', $school->id)
                                    ->where(
                                        'minimum_score',
                                        '<=',
                                        $percentage
                                    )
                                    ->where(
                                        'maximum_score',
                                        '>=',
                                        $percentage
                                    )
                                    ->orderBy(
                                        'minimum_score',
                                        'desc'
                                    )
                                    ->first();


                                /*
                                |--------------------------------------------------------------------------
                                | Grade
                                |--------------------------------------------------------------------------
                                */

                                $gradeDisplay = $configuredGrade
                                    ? (
                                        $configuredGrade->code
                                        ?: $configuredGrade->name
                                    )
                                    : ($mark->grade ?? '—');


                                /*
                                |--------------------------------------------------------------------------
                                | Pass / Fail
                                |--------------------------------------------------------------------------
                                */

                                $markResult = $configuredGrade?->result;


                                $markResultLabel = $markResult
                                    ? ucfirst(
                                        strtolower($markResult)
                                    )
                                    : '—';

                            @endphp


                            <tr>

                                {{-- Number --}}
                                <td class="center">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Subject --}}
                                <td>
                                    {{ $mark->course?->name ?? '—' }}
                                </td>


                                {{-- Score --}}
                                <td class="right">
                                    {{ number_format($score, 2) }}
                                </td>


                                {{-- Maximum --}}
                                <td class="right">
                                    {{ number_format($maximum, 2) }}
                                </td>


                                {{-- Percentage --}}
                                <td class="right">
                                    {{ number_format($percentage, 2) }}%
                                </td>


                                {{-- Grade --}}
                                <td class="center">
                                    {{ $gradeDisplay }}
                                </td>


                                {{-- Result --}}
                                <td
                                    class="center
                                    @if($markResult === 'pass')
                                        pass
                                    @elseif($markResult === 'fail')
                                        fail
                                    @endif"
                                >

                                    {{ $markResultLabel }}

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="center"
                                >
                                    No approved subject marks available.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>


                {{-- ================================================= --}}
                {{-- RESULT SUMMARY                                    --}}
                {{-- ================================================= --}}

                <table class="result-summary">

                    <tr>

                        <td class="label">
                            Total Score
                        </td>

                        <td>

                            {{ number_format(
                                (float) $result->total_score,
                                2
                            ) }}

                        </td>


                        <td class="label">
                            Average
                        </td>

                        <td>

                            {{ number_format(
                                (float) $result->average,
                                2
                            ) }}%

                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Overall Grade
                        </td>

                        <td>
                            {{ $result->grade ?? '—' }}
                        </td>


                        <td class="label">
                            Position
                        </td>

                        <td>
                            {{ $result->position ?? '—' }}
                        </td>

                    </tr>

                </table>

            </div>

        @endforeach


    @else

        <div class="no-results">
            No published academic results were found.
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SIGNATURES                                                --}}
    {{-- ========================================================= --}}

    <table class="signature-area">

        <tr>

            <td>

                <div class="signature-line">
                    Registrar
                </div>

            </td>


            <td>

                <div class="signature-line">
                    Principal
                </div>

            </td>


            <td>

                <div class="signature-line">
                    Official School Stamp
                </div>

            </td>

        </tr>

    </table>


    {{-- ========================================================= --}}
    {{-- FOOTER                                                    --}}
    {{-- ========================================================= --}}

    <div class="footer">

        Official Academic Transcript

        &nbsp; | &nbsp;

        Generated:
        {{ now()->format('d M Y H:i') }}

        &nbsp; | &nbsp;

        Page
        <span class="page-number"></span>

    </div>

</body>

</html>