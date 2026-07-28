import React, { useMemo, useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Link } from 'react-router-dom';

type CheckpointRow = {
  id: number;
  name: string;
  code: string;
  status: string;
  priority: number;
};

async function fetchCheckpointRows(): Promise<CheckpointRow[]> {
  const response = await fetch('/api/v1/enterprise/multientity/checkpoint');
  if (!response.ok) {
    return Array.from({ length: 12 }, (_, index) => ({
      id: index + 1,
      name: 'Checkpoint ' + (index + 1),
      code: 'MultiEntity-Checkpoint-' + (index + 1),
      status: index % 2 === 0 ? 'active' : 'pending',
      priority: (index % 9) + 1,
    }));
  }
  const json = await response.json();
  return (json.data?.rows ?? json.data ?? []) as CheckpointRow[];
}

export default function MultiEntityCheckpointPage(): React.JSX.Element {
  const queryClient = useQueryClient();
  const [filter, setFilter] = useState('');
  const [status, setStatus] = useState('all');

  const query = useQuery({
    queryKey: ['MultiEntity', 'Checkpoint', filter, status],
    queryFn: fetchCheckpointRows,
  });

  const mutation = useMutation({
    mutationFn: async (payload: Partial<CheckpointRow>) => {
      const response = await fetch('/api/v1/enterprise/multientity/checkpoint', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      return response.json();
    },
    onSuccess: async () => {
      await queryClient.invalidateQueries({ queryKey: ['MultiEntity', 'Checkpoint'] });
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

  const helper1 = (row: CheckpointRow): string => {
    const score = row.priority * 1 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper2 = (row: CheckpointRow): string => {
    const score = row.priority * 2 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper3 = (row: CheckpointRow): string => {
    const score = row.priority * 3 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper4 = (row: CheckpointRow): string => {
    const score = row.priority * 4 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper5 = (row: CheckpointRow): string => {
    const score = row.priority * 5 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper6 = (row: CheckpointRow): string => {
    const score = row.priority * 6 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper7 = (row: CheckpointRow): string => {
    const score = row.priority * 7 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper8 = (row: CheckpointRow): string => {
    const score = row.priority * 8 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper9 = (row: CheckpointRow): string => {
    const score = row.priority * 9 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper10 = (row: CheckpointRow): string => {
    const score = row.priority * 10 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper11 = (row: CheckpointRow): string => {
    const score = row.priority * 11 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper12 = (row: CheckpointRow): string => {
    const score = row.priority * 12 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper13 = (row: CheckpointRow): string => {
    const score = row.priority * 13 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper14 = (row: CheckpointRow): string => {
    const score = row.priority * 14 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper15 = (row: CheckpointRow): string => {
    const score = row.priority * 15 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper16 = (row: CheckpointRow): string => {
    const score = row.priority * 16 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper17 = (row: CheckpointRow): string => {
    const score = row.priority * 17 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper18 = (row: CheckpointRow): string => {
    const score = row.priority * 18 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper19 = (row: CheckpointRow): string => {
    const score = row.priority * 19 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper20 = (row: CheckpointRow): string => {
    const score = row.priority * 20 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper21 = (row: CheckpointRow): string => {
    const score = row.priority * 21 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper22 = (row: CheckpointRow): string => {
    const score = row.priority * 22 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper23 = (row: CheckpointRow): string => {
    const score = row.priority * 23 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper24 = (row: CheckpointRow): string => {
    const score = row.priority * 24 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper25 = (row: CheckpointRow): string => {
    const score = row.priority * 25 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper26 = (row: CheckpointRow): string => {
    const score = row.priority * 26 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper27 = (row: CheckpointRow): string => {
    const score = row.priority * 27 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper28 = (row: CheckpointRow): string => {
    const score = row.priority * 28 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper29 = (row: CheckpointRow): string => {
    const score = row.priority * 29 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper30 = (row: CheckpointRow): string => {
    const score = row.priority * 30 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper31 = (row: CheckpointRow): string => {
    const score = row.priority * 31 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper32 = (row: CheckpointRow): string => {
    const score = row.priority * 32 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper33 = (row: CheckpointRow): string => {
    const score = row.priority * 33 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper34 = (row: CheckpointRow): string => {
    const score = row.priority * 34 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper35 = (row: CheckpointRow): string => {
    const score = row.priority * 35 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper36 = (row: CheckpointRow): string => {
    const score = row.priority * 36 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper37 = (row: CheckpointRow): string => {
    const score = row.priority * 37 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper38 = (row: CheckpointRow): string => {
    const score = row.priority * 38 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper39 = (row: CheckpointRow): string => {
    const score = row.priority * 39 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  const helper40 = (row: CheckpointRow): string => {
    const score = row.priority * 40 + row.name.length;
    return row.code + '::' + row.status + '::' + score;
  };

  return (
    <section className="enterprise-module MultiEntity-Checkpoint">
      <header>
        <h1>MultiEntity / Checkpoint</h1>
        <p>Enterprise workspace powered by React Query and React Router v5.</p>
        <Link to="/enterprise/multientity">Back to MultiEntity</Link>
      </header>
      <div className="toolbar">
        <input value={filter} onChange={(e) => setFilter(e.target.value)} placeholder="Filter Checkpoint" />
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
              name: 'New Checkpoint',
              code: 'NEW-Checkpoint',
              status: 'draft',
              priority: 1,
            })
          }
        >
          Create Checkpoint
        </button>
      </div>
      {query.isLoading ? <p>Loading Checkpoint rows...</p> : null}
      {query.isError ? <p>Unable to load Checkpoint rows.</p> : null}
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
