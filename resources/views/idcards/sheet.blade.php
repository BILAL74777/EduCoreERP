<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Identity Cards</title>
    <style>
        <blade page|%20%7B%0D>size: A4 portrait;
        margin: 0;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            padding: 7mm 8mm;
        }

        :root {
            --navy: #012F6B;
            --blue: #023576;
            --yellow: #EDB52A;
            --text: #142f4f;
        }

        .sheet {
            width: 194mm;
            min-height: 283mm;
            display: grid;
            grid-template-columns: repeat(3, 55.88mm);
            grid-auto-rows: 86.36mm;
            column-gap: 10.19mm;
            row-gap: 8mm;
            align-content: start;
            justify-content: center;
            page-break-after: always;
        }

        .sheet:last-child {
            page-break-after: auto;
        }

        /* Exact card size: 2.2in × 3.4in = 55.88mm × 86.36mm */
        .id-card {
            position: relative;
            width: 55.88mm;
            height: 86.36mm;
            overflow: hidden;
            background: #ffffff;
            border-radius: 4.57mm;
            border: 0.35mm solid var(--navy);
            box-shadow: 0 1.2mm 3mm rgba(0, 0, 0, .16);
            color: var(--navy);
            flex-shrink: 0;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* Transparent system-logo overlay */
        .watermark-logo {
            position: absolute;
            left: 50%;
            top: 67%;
            transform: translate(-50%, -50%);
            width: 50mm;
            height: 50mm;
            object-fit: contain;
            opacity: 0.045;
            z-index: 7;
            pointer-events: none;
        }

        /* Header */
        .top-decoration {
            position: absolute;
            inset: 0 0 auto 0;
            width: 100%;
            height: 33.02mm;
            z-index: 1;
        }

        .college-title {
            position: absolute;
            z-index: 8;
            top: 2.413mm;
            left: 2.032mm;
            width: 51.816mm;
            text-align: center;
            color: #ffffff;
            font-weight: 900;
            font-size: 5.08mm;
            line-height: .98;
            letter-spacing: -0.305mm;
            white-space: nowrap;
        }

        .college-subtitle {
            position: absolute;
            z-index: 8;
            top: 9.779mm;
            left: 1.778mm;
            width: 52.324mm;
            text-align: center;
            color: var(--yellow);
            font-weight: 900;
            font-size: 3.251mm;
            line-height: 1;
            white-space: nowrap;
        }

        .location-row {
            position: absolute;
            z-index: 8;
            top: 14.097mm;
            left: 4.318mm;
            width: 47.244mm;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2.54mm;
            color: #ffffff;
            font-size: 2.591mm;
            font-weight: 800;
        }

        .location-row .line {
            flex: 1;
            height: 0.457mm;
            background: var(--yellow);
        }

        /* Logo / photo */
        .logo {
            position: absolute;
            z-index: 10;
            top: 20.32mm;
            left: 50%;
            transform: translateX(-50%);
            width: 20.32mm;
            height: 20.32mm;
            border-radius: 50%;
            background: #ffffff;
            border: 1.143mm solid var(--navy);
            box-shadow: 0 0 0 .381mm #ffffff, 0 0 0 .686mm var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-inner {
            width: 13.716mm;
            height: 13.716mm;
            border-radius: 50%;
            border: 0.305mm solid var(--navy);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .front-photo {
            position: absolute;
            z-index: 10;
            top: 20.32mm;
            left: 50%;
            transform: translateX(-50%);
            width: 25.32mm;
            height: 25.32mm;
            object-fit: cover;
            border-radius: 50%;
            background: #ffffff;
            /* border: 1.143mm solid var(--navy); */
            box-shadow: 0 0 0 .381mm #ffffff, 0 0 0 .686mm var(--navy);
            /* padding: 1.35mm; */
        }

        .front-logo {
            position: absolute;
            z-index: 10;
            top: 20.32mm;
            left: 50%;
            transform: translateX(-50%);
            width: 20.32mm;
            height: 20.32mm;
            object-fit: contain;
            border-radius: 50%;
            background: #ffffff;
            border: 1.143mm solid var(--navy);
            box-shadow: 0 0 0 .381mm #ffffff, 0 0 0 .686mm var(--navy);
            padding: 1.35mm;
        }

        .no-photo {
            position: absolute;
            z-index: 10;
            top: 20.32mm;
            left: 50%;
            transform: translateX(-50%);
            width: 20.32mm;
            height: 20.32mm;
            border-radius: 50%;
            background: linear-gradient(135deg, #e9f0f8, #d9e4f2);
            border: 1.143mm solid var(--navy);
            box-shadow: 0 0 0 .381mm #ffffff, 0 0 0 .686mm var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 6mm;
            font-weight: 900;
        }

        /* Student content */
        .content {
            position: absolute;
            z-index: 12;
            top: 47.672mm;
            left: 2.54mm;
            width: 50.8mm;
            text-align: center;
        }

        .student-name {
            font-size: 4.715mm;
            line-height: 1;
            font-weight: 900;
            letter-spacing: .051mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--navy);
        }

        .name-line {
            width: 44.704mm;
            height: .356mm;
            margin: 1.905mm auto 1.321mm;
            background: var(--navy);
        }

        .father-name {
            font-size: 3.505mm;
            line-height: 1;
            font-weight: 900;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--navy);
        }

        .program {
            margin-top: 1.397mm;
            font-size: 2.591mm;
            line-height: 1;
            color: var(--text);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Barcode */
        .barcode-wrap {
            margin: 2.286mm auto 0;
            width: 36.322mm;
            height: 9.906mm;
            display: flex;
            justify-content: center;
            align-items: stretch;
            overflow: hidden;
        }

        .barcode {
            width: 100%;
            height: 100%;
            display: block;
        }

        .barcode-wrap svg {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .barcode-fallback {
            font-family: "Courier New", monospace;
            font-size: 2.5mm;
            font-weight: bold;
            color: var(--navy);
        }

        .barcode-number {
            margin-top: .635mm;
            font-size: 3.937mm;
            line-height: 1;
            letter-spacing: .889mm;
            color: var(--navy);
            font-weight: 500;
            white-space: nowrap;
            padding-left: .889mm;
        }

        /* Bottom SVG decoration */
        .bottom-decoration {
            position: absolute;
            inset: auto 0 0 0;
            width: 100%;
            height: 13.208mm;
            z-index: 3;
        }

        <blade media|%20screen%20%7B%0D>body {
            background: #eeeeee;
        }

        .sheet {
            margin: 0 auto;
        }

        .id-card {
            box-shadow: 0 5px 18px rgba(0, 0, 0, .16);
        }
        }

        <blade media|%20print%20%7B%0D>html,
        body {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
        }

        body {
            padding: 7mm 8mm;
        }

        .sheet {
            width: 194mm;
            min-height: 283mm;
            page-break-after: always;
        }

        .id-card {
            box-shadow: none;
        }
        }

    </style>
</head>

<body>

    @php

        /*
        * -------------------------------------------------------------
        * GET STUDENT PHOTO
        * -------------------------------------------------------------
        */
        $getPhotoData = function ($student) {
        try {
        if (!$student || empty($student->photo)) {
        return null;
        }

        $upload = \App\Models\Upload::find($student->photo);

        if (!$upload) {
        return null;
        }

        $filePath = public_path(
        'storage/' .
        trim($upload->folder, '/') .
        '/' .
        $upload->file_name
        );

        if (!file_exists($filePath)) {
        return null;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mime = match ($extension) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        default => 'image/jpeg',
        };

        return 'data:' . $mime . ';base64,' .
        base64_encode(file_get_contents($filePath));

        } catch (\Throwable $e) {
        return null;
        }
        };

        /*
        * -------------------------------------------------------------
        * SYSTEM LOGO
        * Used both as the fallback logo and as the transparent
        * background overlay.
        * -------------------------------------------------------------
        */
        $systemLogoPath = public_path('images/logo/transparent-logo.png');

        $systemLogo = null;

        if (file_exists($systemLogoPath)) {
        $systemLogo = 'data:image/png;base64,' .
        base64_encode(file_get_contents($systemLogoPath));
        }

        /*
        * -------------------------------------------------------------
        * STUDENT NAME
        * -------------------------------------------------------------
        */
        $studentName = function ($student) {
        return strtoupper(
        trim($student->name ?? 'STUDENT')
        );
        };

        /*
        * -------------------------------------------------------------
        * FATHER NAME
        * -------------------------------------------------------------
        */
        $fatherName = function ($student) {
        return strtoupper(
        trim($student->father_name ?? 'FATHER NAME')
        );
        };

        /*
        * -------------------------------------------------------------
        * CLASS / PROGRAM
        * -------------------------------------------------------------
        */
        $getClassName = function ($enrollment) {
        return
        $enrollment->academicClass->name ??
        $enrollment->class_name ??
        'CLASS';
        };
        /*
        * -------------------------------------------------------------
        * Discipline
        * -------------------------------------------------------------
        */
        $getDisciplineName = function ($enrollment) {
        return
        $enrollment->academicDiscipline->name ??
        $enrollment->discipline ??
        'DISCIPLINE';
        };

        /*
        * -------------------------------------------------------------
        * CARD NUMBER
        * -------------------------------------------------------------
        */
        $getCardNo = function ($enrollment) {
        return $enrollment->card_no ?? '-';
        };

        /*
        * -------------------------------------------------------------
        * THREE CARDS PER ROW
        * Four cards do not fit physically across an A4 page when each
        * card remains exactly 2.2in wide. Therefore the dynamic layout
        * is 3 cards per row, followed by the next row.
        * -------------------------------------------------------------
        */
        $studentGroups = $enrollments->chunk(9);

    @endphp

    @foreach($studentGroups as $group)

        <div class="sheet">

            @foreach($group as $enrollment)

                @php
                    $student = $enrollment->student;

                    $photo = $getPhotoData($student);
                    $name = $studentName($student);
                    $father = $fatherName($student);
                    $className = $getClassName($enrollment);
                    $disciplineName = $getDisciplineName($enrollment);
                    $cardNo = $getCardNo($enrollment);
                @endphp

                <div class="id-card">

                    {{-- Transparent background watermark --}}
                    @if($systemLogo)
                        <img src="{{ $systemLogo }}" class="watermark-logo" alt="" aria-hidden="true">
                    @endif

                    {{-- =====================================================
                     TOP DECORATION — SAME DESIGN AS PROVIDED
                ====================================================== --}}
                    <svg class="top-decoration" viewBox="0 0 220 130" preserveAspectRatio="none"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill="#012F6B" d="M0 0H220V76
                      C178 91 151 101 110 105
                      C69 101 42 91 0 76Z" />

                        <path fill="#EDB52A" d="M0 75
                      C42 90 70 100 110 104
                      C150 100 178 90 220 75
                      L220 84
                      C178 100 150 110 110 114
                      C70 110 42 100 0 84Z" />

                        <path fill="#023576" d="M0 84
                      C42 100 70 110 110 114
                      C150 110 178 100 220 84
                      L220 100
                      C179 115 150 124 110 128
                      C70 124 41 115 0 100Z" />

                        <path fill="#FFFFFF" d="M0 96
                      C42 111 70 121 110 125
                      C150 121 178 111 220 96
                      L220 130H0Z" />
                    </svg>

                    <div class="college-title">Modern Era College</div>
                    <div class="college-subtitle">of Science and Technology</div>

                    <div class="location-row">
                        <span class="line"></span>
                        <span>Samar Bagh</span>
                        <span class="line"></span>
                    </div>

                    {{-- =====================================================
                     STUDENT PHOTO / LOGO
                ====================================================== --}}
                    @if($photo)

                        <img src="{{ $photo }}" class="front-photo" alt="Student Photo">

                    @elseif($systemLogo)

                        <img src="{{ $systemLogo }}" class="front-logo" alt="College Logo">

                    @else

                        <div class="no-photo">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </div>

                    @endif

                    {{-- =====================================================
                     STUDENT CONTENT
                ====================================================== --}}
                    <div class="content">

                        <div class="student-name">
                            {{ $name }}
                        </div>

                        <div class="name-line"></div>

                        <div class="father-name">
                            {{ $father }}
                        </div>

                        <div class="program">
                            {{ $disciplineName->name }} - {{ $className }}
                        </div>

                        {{-- =================================================
                         DYNAMIC BARCODE
                    ================================================== --}}
                        <div class="barcode-wrap">

                            @if(!empty($enrollment->barcode_svg))

                                {!! $enrollment->barcode_svg !!}

                            @else

                                <div class="barcode-fallback">
                                    {{ $cardNo }}
                                </div>

                            @endif

                        </div>

                        <!-- <div class="barcode-number">
                            {{ $cardNo }}
                        </div> -->

                    </div>

                    {{-- =====================================================
                     BOTTOM DECORATION — SAME DESIGN AS PROVIDED
                ====================================================== --}}
                    <svg class="bottom-decoration" viewBox="0 0 220 52" preserveAspectRatio="none"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path fill="#EDB52A" d="M0 0
                      C42 12 74 23 110 29
                      C146 23 178 12 220 0
                      L220 10
                      C178 23 146 34 110 40
                      C74 34 42 23 0 10Z" />

                        <path fill="#012F6B" d="M0 10
                      C42 23 74 34 110 40
                      C146 34 178 23 220 10
                      L220 52H0Z" />

                        <path fill="#023576" d="M0 15
                      C30 25 61 38 110 52
                      H0Z" />

                        <path fill="#023576" d="M220 15
                      C190 25 159 38 110 52
                      H220Z" />
                    </svg>

                </div>

            @endforeach

        </div>

    @endforeach

</body>

</html>
