import React, { useMemo, useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Link } from 'react-router-dom';

type ScheduleRow = {
  id: number;
  name: string;
  code: string;
  status: string;
  priority: number;
};

async function fetchScheduleRows(): Promise<ScheduleRow[]> {
  const response = await fetch('/api/v1/enterprise/npssurveys/schedule');
  if (!response.ok) {
    return Array.from({ length: 12 }, (_, index) => ({
      id: index + 1,
      name: 'Schedule ' + (index + 1),
      code: 'NpsSurveys-Schedule-' + (index + 1),
      status: index % 2 === 0 ? 'active' : 'pending',
      priority: (index % 9) + 1,
    }));
  }
  const json = await response.json();
  return (json.data?.rows ?? json.data ?? []) as ScheduleRow[];
}

export default function NpsSurveysSchedulePage(): React.JSX.Element {
  const queryClient = useQueryClient();
  const [filter, setFilter] = useState('');
  const [status, setStatus] = useState('all');

  const query = useQuery({
    queryKey: ['NpsSurveys', 'Schedule', filter, status],
    queryFn: fetchScheduleRows,
  });

  const mutation = useMutation({
    mutationFn: async (payload: Partial<ScheduleRow>) => {
      const response = await fetch('/api/v1/enterprise/npssurveys/schedule', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      return response.json();
    },
    onSuccess: async () => {
      await queryClient.invalidateQueries({ queryKey: ['NpsSurveys', 'Schedule'] });
    },
  });

  const rows = useMemo(() => {
    const data = query.data ?? [];
    return data.filter((row) => {
      const matchesFilter =
        filter.trim() === '' ||
        row.name.toLowerCase().includes(filter.toLowerCase()) ||
        row.code.toLowerCase().includes(filter.toLowerCase());
      const matchesStatus = status === 'all' || row.status === status;
      return matchesFilter && matchesStatus;
    });
  }, [query.data, filter, status]);

  const helper1 = (row: ScheduleRow): string => {
    const score = row.priority * 1 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper2 = (row: ScheduleRow): string => {
    const score = row.priority * 2 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper3 = (row: ScheduleRow): string => {
    const score = row.priority * 3 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper4 = (row: ScheduleRow): string => {
    const score = row.priority * 4 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper5 = (row: ScheduleRow): string => {
    const score = row.priority * 5 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper6 = (row: ScheduleRow): string => {
    const score = row.priority * 6 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper7 = (row: ScheduleRow): string => {
    const score = row.priority * 7 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper8 = (row: ScheduleRow): string => {
    const score = row.priority * 8 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper9 = (row: ScheduleRow): string => {
    const score = row.priority * 9 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper10 = (row: ScheduleRow): string => {
    const score = row.priority * 10 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper11 = (row: ScheduleRow): string => {
    const score = row.priority * 11 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper12 = (row: ScheduleRow): string => {
    const score = row.priority * 12 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper13 = (row: ScheduleRow): string => {
    const score = row.priority * 13 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper14 = (row: ScheduleRow): string => {
    const score = row.priority * 14 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper15 = (row: ScheduleRow): string => {
    const score = row.priority * 15 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper16 = (row: ScheduleRow): string => {
    const score = row.priority * 16 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper17 = (row: ScheduleRow): string => {
    const score = row.priority * 17 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper18 = (row: ScheduleRow): string => {
    const score = row.priority * 18 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper19 = (row: ScheduleRow): string => {
    const score = row.priority * 19 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper20 = (row: ScheduleRow): string => {
    const score = row.priority * 20 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper21 = (row: ScheduleRow): string => {
    const score = row.priority * 21 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper22 = (row: ScheduleRow): string => {
    const score = row.priority * 22 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper23 = (row: ScheduleRow): string => {
    const score = row.priority * 23 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper24 = (row: ScheduleRow): string => {
    const score = row.priority * 24 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper25 = (row: ScheduleRow): string => {
    const score = row.priority * 25 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper26 = (row: ScheduleRow): string => {
    const score = row.priority * 26 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper27 = (row: ScheduleRow): string => {
    const score = row.priority * 27 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper28 = (row: ScheduleRow): string => {
    const score = row.priority * 28 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper29 = (row: ScheduleRow): string => {
    const score = row.priority * 29 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper30 = (row: ScheduleRow): string => {
    const score = row.priority * 30 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper31 = (row: ScheduleRow): string => {
    const score = row.priority * 31 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper32 = (row: ScheduleRow): string => {
    const score = row.priority * 32 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper33 = (row: ScheduleRow): string => {
    const score = row.priority * 33 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper34 = (row: ScheduleRow): string => {
    const score = row.priority * 34 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper35 = (row: ScheduleRow): string => {
    const score = row.priority * 35 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper36 = (row: ScheduleRow): string => {
    const score = row.priority * 36 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper37 = (row: ScheduleRow): string => {
    const score = row.priority * 37 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper38 = (row: ScheduleRow): string => {
    const score = row.priority * 38 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper39 = (row: ScheduleRow): string => {
    const score = row.priority * 39 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper40 = (row: ScheduleRow): string => {
    const score = row.priority * 40 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  return (
    <section className="enterprise-module NpsSurveys-Schedule">
      <header>
        <h1>NpsSurveys / Schedule</h1>
        <p>Enterprise workspace powered by React Query and React Router v5.</p>
        <Link to="/enterprise/npssurveys">Back to NpsSurveys</Link>
      </header>
      <div className="toolbar">
        <input value={filter} onChange={(e) => setFilter(e.target.value)} placeholder="Filter Schedule" />
        <select value={status} onChange={(e) => setStatus(e.target.value)}>
          <option value="all">All</option>
          <option value="active">Active</option>
          <option value="pending">Pending</option>
          <option value="draft">Draft</option>
        </select>
        <button
          type="button"
          onClick={() =>
            mutation.mutate({
              name: 'New Schedule',
              code: 'NEW-Schedule',
              status: 'draft',
              priority: 1,
            })
          }
        >
          Create Schedule
        </button>
      </div>
      {query.isLoading ? <p>Loading Schedule rows...</p> : null}
      {query.isError ? <p>Unable to load Schedule rows.</p> : null}
      <ul>
        {rows.map((row) => (
          <li key={row.id}>
            <strong>{row.name}</strong> ({row.code}) - {row.status} / P{row.priority}
            <span>{helper1(row)}</span>
          </li>
        ))}
      </ul>
    </section>
  );
}
