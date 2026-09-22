<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\QtInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->merge([
            'pic_no' => $request->pic_no === '' ? null : $request->pic_no,
        ]);

        $validated = $request->validate([
            'project_name'   => 'required|string|max:255',
            'period'         => 'nullable|string|max:255',
            'client_name'    => 'nullable|string|max:255',
            'client_address' => 'nullable|string|max:1000',
            'pic'            => 'nullable|string|max:255',
            'pic_no'         => 'nullable|string|max:20',
            'location'       => 'nullable|string|max:100',
        ]);

        try {
            $project = DB::transaction(function () use ($validated, $id) {
                $userId = Auth::id();

                $project = Project::where('project_Id', $id)->firstOrFail();
                $nameChanged = false;

                // Update or create the related client
                if (!empty($validated['client_name'])) {
                    if ($project->client_Id) {
                        $client = Client::where('client_Id', $project->client_Id)->firstOrFail();
                        $nameChanged = $client->company_name !== $validated['client_name'];

                        $client->update([
                            'company_name'   => $validated['client_name'],
                            'client_address' => $validated['client_address'] ?? null,
                            'updated_by'     => $userId,
                        ]);
                    } else {
                        $client = Client::create([
                            'company_name'   => $validated['client_name'],
                            'client_address' => $validated['client_address'] ?? null,
                            'created_by'     => $userId,
                        ]);
                        $project->client_Id = $client->client_Id;
                        $nameChanged = true;
                    }
                }

                // Update project fields
                $project->name       = $validated['project_name'];
                $project->period     = $validated['period'] ?? $project->period;
                $project->pic_name   = $validated['pic'] ?? $project->pic_name;
                $project->pic_no     = $validated['pic_no'] ?? $project->pic_no;
                $project->location   = $validated['location'] ?? $project->location;
                $project->updated_by = $userId;
                $project->save();

                // Client name changed: renumber ALL projects of this client
                // (and their quotations) with the new client initials
                if ($nameChanged) {
                    $code = $this->clientCode($validated['client_name']);

                    Project::where('client_Id', $project->client_Id)
                        ->get()
                        ->each(fn ($p) => $this->renumber($p, $code));
                }

                return $project->fresh('client');
            });

            return response()->json([
                'success' => true,
                'message' => 'Project details updated successfully!',
                'project' => [
                    'project_Id'     => $project->project_Id,
                    'name'           => $project->name,
                    'number'         => $project->number,
                    'period'         => $project->period,
                    'pic_name'       => $project->pic_name,
                    'pic_no'         => $project->pic_no,
                    'client_name'    => $project->client?->company_name,
                    'client_address' => $project->client?->client_address,
                    'location'       => $project->location,
                ],
            ], 200);

        // Duplicate number gives a clear message instead of "Database error"
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Change the 3rd part of one project's number (EHS/TYPE/CODE/RUNNING)
    // and carry the new number into that project's quotations
    private function renumber(Project $p, string $code): void
    {
        $old   = (string) $p->number;
        $parts = explode('/', $old);
        if (count($parts) !== 4) {
            return;
        }

        $parts[2] = $code;
        $new = implode('/', $parts);
        if ($new === $old) {
            return;
        }

        $taken = Project::where('number', $new)
            ->where('project_Id', '!=', $p->project_Id)
            ->exists();

        if ($taken) {
            throw new \DomainException("Project number {$new} already exists.");
        }

        $p->number = $new;
        $p->save();

        QtInvoice::where('project_Id', $p->project_Id)
            ->get()
            ->each(function ($q) use ($old, $new) {
                $q->quotation_no = Str::replaceFirst($old, $new, $q->quotation_no);
                $q->save();
            });
    }

    // "alya com try" -> "ACT"
    private function clientCode(string $name): string
    {
        return collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }
}