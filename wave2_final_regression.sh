#!/usr/bin/env bash
set -u

echo "============================================================"
echo "SMS Wave 2 — Final Regression & Security Verification"
echo "============================================================"
echo

fail=0

run_check() {
  local label="$1"
  shift
  echo "---- $label"
  if "$@"; then
    echo "PASS: $label"
  else
    echo "FAIL: $label"
    fail=1
  fi
  echo
}

run_check "Laravel version" php artisan --version
run_check "PHP version" php -v
run_check "Clear Laravel caches" php artisan optimize:clear

echo "---- PHP syntax checks"
php -l app/Services/AcademicHierarchyService.php || fail=1
php -l app/Services/TransportService.php || fail=1
php -l app/Http/Controllers/Admin/TransportController.php || fail=1
php -l app/Http/Requests/StoreRouteStudentRequest.php || fail=1
php -l app/Http/Requests/StoreTransportFeeRequest.php || fail=1
php -l app/Http/Controllers/Admin/HostelController.php || fail=1
php -l app/Http/Controllers/Admin/HostelFeeController.php || fail=1
php -l app/Services/HostelService.php || fail=1
php -l app/Http/Requests/StoreHostelAllocationRequest.php || fail=1
php -l app/Http/Requests/StoreHostelFeeRequest.php || fail=1
echo

echo "---- Wave 2 route counts"
echo "Library:"
php artisan route:list --path=admin/library | tail -n +1
echo
echo "Transport:"
php artisan route:list --path=admin/transport | tail -n +1
echo
echo "Hostel:"
php artisan route:list --path=admin/hostel | tail -n +1
echo

echo "---- Obsolete controller references"
if grep -RIn --exclude-dir=vendor --exclude-dir=storage \
  -E 'HostelMasterController|TransportMasterController' routes app/Http app/Services resources/views 2>/dev/null; then
  echo "FAIL: obsolete controller references found."
  fail=1
else
  echo "PASS: no obsolete HostelMasterController/TransportMasterController references."
fi
echo

echo "---- Constructor middleware anti-pattern"
if grep -RIn --exclude-dir=vendor --exclude-dir=storage \
  -E 'function __construct[[:space:]]*\([^)]*\)[[:space:]]*\{[^}]*\$this->middleware|\\$this->middleware\(' \
  app/Http/Controllers 2>/dev/null; then
  echo "WARNING: inspect constructor/controller middleware references above."
else
  echo "PASS: no obvious controller constructor middleware anti-pattern found."
fi
echo

echo "---- Cross-school query smoke scan"
echo "These are manual-review candidates, not automatic failures:"
grep -RIn --exclude-dir=vendor --exclude-dir=storage \
  -E 'RouteStudent::(find|findOrFail)|TransportFee::(find|findOrFail)|TransportRoute::(find|findOrFail)|TransportStop::(find|findOrFail)|HostelAllocation::(find|findOrFail)|HostelFee::(find|findOrFail)|HostelRoom::(find|findOrFail)|HostelBed::(find|findOrFail)' \
  app/Http app/Services 2>/dev/null || true
echo

echo "============================================================"
if [ "$fail" -eq 0 ]; then
  echo "STATIC CHECKS: PASS"
else
  echo "STATIC CHECKS: FAIL — review the output above."
fi
echo "============================================================"
exit "$fail"
