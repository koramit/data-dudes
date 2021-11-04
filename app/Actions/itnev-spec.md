# Itnev data

## Active Patients

* `id` => number ref_id
* `doc.isInOfficeHour` => boolean: overtime
* `doc.fname` => string: full name
* `doc.sex` => string[ชาย,หญิง]: gender
* `doc.hn`
* `doc.birthdate` => date ISO: dob
* `doc.anonymouse` => boolean:
* `doc.queue` => string[ER, Doctor?]:
* `doc.isQueue` => boolean:
* `doc.Tcheckin` => timestamp: encountered_at
* `doc.modeArrival` => string['walk-in']:
* `doc.cc` => string:
* `doc.zone` => string[nonTraumaUnit, observationUnit, ?]:
* `doc.position` => string: tag_number
* `doc.acuityLVL` => number: severity_level
* `doc.scheme` => string: insurance
* `doc.triaged` => boolean:
* `doc.TfinishTriage` => timestamp: triaged_at
* `doc.zoneName` => string[counter 3, ?]:
* `doc.remark` => string:
* `doc.CPR` => boolean:
* `doc.isTube` => boolean:
* `doc.isPinned` => boolean:
* `doc.isObserve` => boolean:
* `doc.isConsultMed` => boolean:
* `doc.dx` => string:
* `doc.movementType` => string[stretcher, ?]:
* `doc.bpSys` => number:
* `doc.bpDias` => number:
* `doc.temp` => number:
* `doc.pr` => number:
* `doc.rr` => number:
* `doc.o2` => number:
* `doc.vitalSignTime` => timestamp:

## Patient Profile
* `specialCase[n].nameTH`

## Cards
* `id` => string:
* `doc.cardType` => string:[order, diag, note, triage]
* `doc.status` => number[0 == red, 1 == yellow, 2 == checked, unset == gray]:
* `doc.TfinishItem` => timestamp:

- order -
* `doc.orderList` => array:
* `doc.orderList[n].name` => string:

- consult -
* `doc.consultDepartment.name`
* `doc.consultDepartment.selectedSubdep`

- note -
* `doc.text` => string:

- dispose -
* `doc.disposeType` => string
